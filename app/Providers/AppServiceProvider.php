<?php

namespace App\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**  */
    protected function schedule(Schedule $schedule)
    {
//        include __DIR__ . 'routes' . DIRECTORY_SEPARATOR . 'schedule.php';
        require base_path('routes/schedule.php');
    }
}
