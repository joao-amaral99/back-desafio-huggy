<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\ContactService;
use App\Services\Contracts\ContactServiceInterface;
use App\Services\ExternalWebhookService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ContactServiceInterface::class, ContactService::class);
        $this->app->singleton(ExternalWebhookService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
