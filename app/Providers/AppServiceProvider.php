<?php

namespace App\Providers;

use App\LogReader;
use App\LogParser;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Foundation\Application;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(LogReader::class, function (Application $app) {
            $fileSystem = Storage::createLocalDriver(['root' => storage_path('logs')]);

            return new LogReader($app->make(LogParser::class), $fileSystem);
        });
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
}
