<?php

namespace App\Providers;

use Laravel\Sanctum\Sanctum;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use App\Events\UserRegistered;
use App\Listeners\SendWelcomeEmail;
use App\Listeners\SendVerificationEmail;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);
        Event::listen(UserRegistered::class, [
            SendVerificationEmail::class,
            SendWelcomeEmail::class,
        ]);
    }
}
