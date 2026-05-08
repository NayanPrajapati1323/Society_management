<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate maintenance bills on the 1st of every month
        $schedule->job(new \App\Jobs\GenerateMonthlyMaintenanceBills)->monthlyOn(1, '00:00');

        // Apply penalties daily
        $schedule->job(new \App\Jobs\ApplyOverduePenalties)->daily();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
