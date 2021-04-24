<?php

namespace App\Http\Controllers;

use Event;
use App\Models\Topic;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Events\MessagePublished;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Publish a message
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $topicId
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request, $topicId)
    {
        $validator  =   Validator::make($request->all(), [
            "message"  =>  "required",
        ]);

        if($validator->fails()) {
            return formatResponse(400, $validator->errors());
        }

        try {
            $topic = Topic::findOrFail($topicId);

            Event::dispatch(new MessagePublished($request->message, $topic));

            $data = [
                "message" => $request->message, 
                "topic" => $topic->topic
            ];

            return formatResponse(200, "Message published", true, $data);
        } catch (ModelNotFoundException $mnfe) {
            return formatResponse(fetchErrorCode($mnfe), get_class($mnfe) . ": " . $mnfe->getMessage());
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }    
    }

    /**
     * Create a subscription
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $topicId
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request, $topicId)
    {
        $validator  =   Validator::make($request->all(), [
            "url"  =>  "required",
        ]);

        if($validator->fails()) {
            return formatResponse(400, $validator->errors());
        }

        try {
            $topic = Topic::findOrFail($topicId);

            $subscription = Subscription::firstOrCreate([
                'url' => $request->url,
                'topic_id' => $topic->id
            ]);

            $data = [
                "url" => $subscription->url, 
                "topic" => $topic->topic
            ];

            return formatResponse(200, "Subscribed", true, $data);
        } catch (ModelNotFoundException $mnfe) {
            return formatResponse(fetchErrorCode($mnfe), get_class($mnfe) . ": " . $mnfe->getMessage());
        } catch (Exception $e) {
            return formatResponse(fetchErrorCode($e), get_class($e) . ": " . $e->getMessage());
        }    
    }
}
