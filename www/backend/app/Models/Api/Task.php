<?php

namespace App\Models\Api;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 * @property int $id
 * @property string $name
 * @property string $time
 */
class Task extends Model
{
    use HasFactory;

    // Периоды. 1 - утро (8, 9, 10, 11, 12) , 2 - день (13, 14, 15, 16, 17) , 3 - вечер (18, 19, 20, 21, 22)
    const PERIODS = [1, 2, 3];
    const MORNING_HOURS = [8, 9, 10, 11, 12];
    const DAY_HOURS = [13, 14, 15, 16, 17];
    const EVENING_HOURS = [18, 19, 20, 21, 22];
    const HOUR_PERIODS = [0, 1, 2, 3, 4, 5];
    const MINUTE_PERIODS = [0, 1, 2, 3, 4, 5, 6, 7, 8, 9];

    // Each transaction for the next day
    // First chose between periods of the day
    // Next hour in period
    // Next 10 minutes period in 1 hour
    // Next random minute
    public function calculateAndSaveNextSwapTime(): void
    {
        $randomPeriod = self::PERIODS[array_rand(self::PERIODS)];
        $hours = self::getHoursByPeriod($randomPeriod);
        $hour = $hours[array_rand($hours)] - 7;
        $hour_period = self::HOUR_PERIODS[array_rand(self::HOUR_PERIODS)];
        $minute_period = self::MINUTE_PERIODS[array_rand(self::MINUTE_PERIODS)];
        $minuteResult = $hour_period * 10 + $minute_period;

        $nextWeekDay = $this->nextWeekDay($this->getWeekDay());

        $nextTime = $minuteResult . ' ' . $hour . ' * * ' . $nextWeekDay;
        $task = new Task();
        $task->name = 'notifydm:cron';
        $task->time = $nextTime;
        $task->save();
    }

    public static function getHoursByPeriod(int $period): array
    {
        return match ($period) {
            1 => self::MORNING_HOURS,
            2 => self::DAY_HOURS,
            3 => self::EVENING_HOURS,
        };
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
