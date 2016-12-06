<?php namespace Backend\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        'Backend\Console\Commands\AccessLogCompress',
        'Backend\Console\Commands\AccessLogDecompress',
        'Backend\Console\Commands\ClearTmpFile'
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('storage:clear-tmp')->dailyAt('13:00');
    }
}
