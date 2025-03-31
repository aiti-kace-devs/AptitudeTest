<?php

namespace App\Providers;

use App\Events\FormSubmittedEvent;
use App\Events\UserRegistered;
use App\Listeners\FormSubmitedListener;
use App\Listeners\SendExamLoginCredentials;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        UserRegistered::class => [
            SendExamLoginCredentials::class,
        ],
        FormSubmittedEvent::class => [
            FormSubmitedListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
