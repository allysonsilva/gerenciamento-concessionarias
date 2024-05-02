<?php

namespace App\Providers;

use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\Registered;
use App\Listeners\UserSuccessfullyVerified;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Verified::class => [
            UserSuccessfullyVerified::class,
        ],
    ];
}
