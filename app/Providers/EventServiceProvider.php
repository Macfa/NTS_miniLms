<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\Admin\Mail\StoreCourseEvent;
use App\Listeners\SendEmailListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StoreCourseEvent::class => [
            SendEmailListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
