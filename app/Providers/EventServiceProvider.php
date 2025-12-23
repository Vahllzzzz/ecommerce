<?php

namespace App\Providers;
use Illuminate\Auth\Events\Login;
use App\Listeners\MergeCartListener;

use Illuminate\Support\ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
    
    protected $listen = [
        Login::class => [
            MergeCartListener::class,
        ],
    ];
}
