<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Api\Balance;
use App\Models\Api\Server;
use App\Models\Api\Task;
use App\Models\Api\Token;
use App\Models\Api\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Http;
use phpseclib3\Net\SSH2;

class TaskController extends Controller
{
    // Периоды. 1 - утро (8, 9, 10, 11, 12) , 2 - день (13, 14, 15, 16, 17) , 3 - вечер (18, 19, 20, 21, 22)
    const PERIODS = [1, 2, 3];
    const MORNING_HOURS = [8, 9, 10, 11, 12];
    const DAY_HOURS = [13, 14, 15, 16, 17];
    const EVENING_HOURS = [18, 19, 20, 21, 22];
    const HOUR_PERIODS = [0, 1, 2, 3, 4, 5];
    const MINUTE_PERIODS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    // CONST for day pause between two swaps
    const PAUSE = [1, 1, 2];

    public int $selectedPeriod = 0;
    public int $currentDay = 1;
    public int $lastTransactionId = 0;

    public function planWeekSchedule()
    {
        /** @var Transaction $lastTransaction */
        $lastTransaction = Transaction::orderBy('id', 'DESC')->where('is_complete', 1)->first();
        $this->lastTransactionId = $lastTransaction->id;
        $nextDay = $this->nextWeekDay($this->currentDay);
        while ($nextDay < 8) {
            if ($lastTransaction->token_id_to == 1) {
                $this->calculateEthSwap();
            } else {
                $this->calculateStableSwap();
            }
            $nextDay = $this->nextWeekDay($this->currentDay);
            /** @var Transaction $lastTransaction */
            $lastTransaction = Transaction::orderBy('id', 'DESC')->where('is_complete', 0)->first();
            $this->lastTransactionId = $lastTransaction->id;
        }
    }

    public function calculateStableSwap()
    {
        $task = new Task();
        $newPeriodsArray = array_slice(self::PERIODS, $this->selectedPeriod + 1);
        $randomPeriodIndex = array_rand($newPeriodsArray);
        $randomPeriod = $newPeriodsArray[$randomPeriodIndex];
        $hours = self::getHoursByPeriod($randomPeriod);
        $hour = $hours[array_rand($hours)];
        $hour_period = self::HOUR_PERIODS[array_rand(self::HOUR_PERIODS)];
        $minute_period = self::MINUTE_PERIODS[array_rand(self::MINUTE_PERIODS)];
        $minuteResult = $hour_period * 10 + $minute_period;

        $nextTime = $minuteResult . ' ' . $hour . ' * * ' . $this->currentDay;
        $task->name = 'swap:cron';
        $task->time = $nextTime;

        $transaction = new Transaction();
        $transaction->createTransaction();

        $task->transaction_id = $this->lastTransactionId + 1;
        $task->save();

        $randomPause = self::PAUSE[array_rand(self::PAUSE)];
        $this->currentDay = $this->currentDay + $randomPause;
    }

    public function calculateEthSwap()
    {
        $task = new Task();
        $periodsArray = [1, 2];
        $randomPeriodIndex = array_rand($periodsArray);
        $this->selectedPeriod = $randomPeriodIndex;
        $randomPeriod = $periodsArray[$randomPeriodIndex];
        $hours = self::getHoursByPeriod($randomPeriod);
        $hour = $hours[array_rand($hours)];
        $hour_period = self::HOUR_PERIODS[array_rand(self::HOUR_PERIODS)];
        $minute_period = self::MINUTE_PERIODS[array_rand(self::MINUTE_PERIODS)];
        $minuteResult = $hour_period * 10 + $minute_period;

        $nextTime = $minuteResult . ' ' . $hour . ' * * ' . $this->currentDay;
        $task->name = 'swap:cron';
        $task->time = $nextTime;

        $transaction = new Transaction();
        $transaction->createTransaction();

        $task->transaction_id = $this->lastTransactionId + 1;
        $task->save();
    }
    // Each transaction for the next day
    // First chose between periods of the day
    // Next hour in period
    // Next 10 minutes period in 1 hour
    // Next random minute
//    public function calculateAndSaveNextSwapTime(): Task
//    {
//        $randomPeriod = self::PERIODS[array_rand(self::PERIODS)];
//        $hours = self::getHoursByPeriod($randomPeriod);
//        $hour = $hours[array_rand($hours)];
//        $hour_period = self::HOUR_PERIODS[array_rand(self::HOUR_PERIODS)];
//        $minute_period = self::MINUTE_PERIODS[array_rand(self::MINUTE_PERIODS)];
//        $minuteResult = $hour_period * 10 + $minute_period;
//
//        $nextWeekDay = $this->nextWeekDay($this->getWeekDay());
//        $nextTime = $minuteResult . ' ' . $hour . ' * * ' . $nextWeekDay;
//        $task = new Task();
//        $task->name = 'swap:cron';
//        $task->time = $nextTime;
//
//        $transaction = new Transaction();
//        $transaction->createTransaction();
//
//        $task->transaction_id = $this->transaction_id + 1;
//        $task->save();
//
//        return $task;
////        $task->saveData();
//    }

    public static function getHoursByPeriod(int $period): array
    {
        return match ($period) {
            1 => self::MORNING_HOURS,
            2 => self::DAY_HOURS,
            3 => self::EVENING_HOURS,
        };
    }

    public function sendTasksToHub()
    {
        /** @var Task[] $tasks */
        $tasks = Task::all();
        foreach ($tasks as $task) {
            $homeUrl = 'http://94.130.132.22/api/v1/create-transaction';
            /** @var Transaction $transaction */
            $transaction = Transaction::find($task->transaction_id);
            $responseObject = Http::post(
                $homeUrl, [
                    'location' => 'Boy2',
                    'time' => $task->time,
                    'token_id_from' => $transaction->token_id_from,
                    'token_id_to' => $transaction->token_id_to,
                    'amount' => $transaction->amount,
                    'transaction_id' => $task->transaction_id,
                    'is_complete' => $transaction->is_complete,
                    'day' => $task->getWeekDay(),
                ]
            )->object();
            sleep(2);
        }
    }

    public function getMinute(): int
    {
        $array = explode(' ', $this->time);
        return (int)$array[0];
    }

    public function getHour(): int
    {
        $array = explode(' ', $this->time);
        return (int)$array[1];
    }

    public function getWeekDay(): int
    {
        $array = explode(' ', $this->time);
        return (int)$array[4];
    }

    public function nextWeekDay(int $weekday): int
    {
        if ($weekday == 7) {
            return 1;
        }
        return $weekday + 1;
    }
}
