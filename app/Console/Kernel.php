<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
// use App\Console\Commands\SendReminderEmail;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // The first way, it is the simplest way to run schedule.
        $schedule->command('reminder:send')
            ->weekdays()
            ->hourly()
            ->between('10:00', '19:00');

        /**
         * The second way, if using this way, it should uncomment "use App\Console\Commands\SendReminderEmail;" on the top.
         */
        // $schedule->command(SendReminderEmail::class)
        // ->weekdays()
        // ->hourly()
        // ->between('10:00', '19:00');

        // This is the third way, but the function invoke should has callback.
        // $schedule->call(function () {
        //     (new SendReminderEmail)();
        // })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
