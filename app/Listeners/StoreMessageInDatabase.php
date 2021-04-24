<?php

namespace App\Listeners;

use App\Models\Message;
use App\Events\MessagePublished;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class StoreMessageInDatabase
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
     * @param  MessagePublished  $event
     * @return void
     */
    public function handle(MessagePublished $event)
    {
        try {
            $message = new Message;
            $message->message = $event->message;
            $message->topic_id = $event->topic->id;
            $message->save();

            return response()->json("Message created");
        } catch (Exception $e) {
            return response()->json($e->getMessage());
        }    
    }
}
