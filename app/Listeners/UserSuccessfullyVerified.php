<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;

class UserSuccessfullyVerified
{
    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        /** @var \App\Models\User */
        $user = $event->user;

        $user->activateAccount();
    }
}
