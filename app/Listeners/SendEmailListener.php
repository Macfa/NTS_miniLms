<?php

namespace App\Listeners;

use App\Events\StoreProgramEvent;
use App\Models\Program;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {

    }

    /**
     * Handle the event.
     */
    public function handle(StoreProgramEvent $event): void
    {
        
    }
}
