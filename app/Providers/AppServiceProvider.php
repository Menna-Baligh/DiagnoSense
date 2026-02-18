<?php

namespace App\Providers;

use App\Events\UserRegistered;
use App\Listeners\SendVerificationEmail;
use App\Listeners\SendWelcomeEmail;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\PersonalAccessToken;
use Laravel\Sanctum\Sanctum;

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
