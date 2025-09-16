<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\Admin\Mail\StoreProgramEvent;
use App\Listeners\SendEmailListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        StoreProgramEvent::class => [
            SendEmailListener::class,
        ],
    ];

    public function boot()
    {
        parent::boot();
    }
}
