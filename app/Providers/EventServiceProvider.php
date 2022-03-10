<?php

namespace App\Providers;

use App\Events\NotificationEvent;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Listeners\SendNotification;
use Illuminate\Notifications\Notification;
use function Illuminate\Events\queueable;
use Throwable;

class EventServiceProvider extends ServiceProvider
{
    
    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
        Event::listen(
            NotificationEvent::class,
            [
                SendNotification::class, 'handel'
            ]
        );

        Event::listen(function(NotificationEvent $event){
            
        });

        // Event::listen(queueable(function (NotificationEvent $event) {
        //     //
        // })->catch(function (NotificationEvent $event, Throwable $e) {
        //     // The queued listener failed...
        // }));
    }
}
