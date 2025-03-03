<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use App\Jobs\SendImagesToUserJob;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendImagesToUser
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        SendImagesToUserJob::dispatch($event->order);//->onQueue('send_images_to_user');
    }
}
