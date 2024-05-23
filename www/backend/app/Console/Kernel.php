<?php

namespace App\Console;

use App\Models\Api\Task;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
//        $swapTask = Task::find(1);
////        $swapTask->calculateAndSaveNextSwapTime();
////        $balanceTask = Task::where(['id' => 2])->first();
//        $schedule->command($swapTask->name)->cron($swapTask->time);
////        $schedule->command($balanceTask->name)->cron($balanceTask->time);
////        $swapTask->calculateAndSaveNextSwapTime();
        /** @var Task $task */
        $task = Task::orderBy('id', 'DESC')->first();
        $task->calculateAndSaveNextSwapTime();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
