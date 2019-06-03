<?php

namespace App\Console;

use App\Models\User;
use App\Notifications\SignupActivate;
use Carbon\Carbon;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $user  = User::where('active', 0)->first();
        $expirationDate = $user->created_at->add(3, 'day');
        $schedule->command('unactiveAccountMail:send')->daily()->when(function () use($expirationDate){
            return date(now()) == $expirationDate;
        });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
