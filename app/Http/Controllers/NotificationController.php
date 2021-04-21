<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $topic
     * @return \Illuminate\Http\Response
     */
    public function publish(Request $request, $topic)
    {
        return 'publish';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string $topic
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request, $topic)
    {
        return 'subscribe';
    }
}
