<?php

namespace App\Http\Controllers\Github;

use App\Http\Controllers\Controller;
use App\Models\Github\Events;
use App\Services\Github;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function webhook(\Illuminate\Http\Request $request)
    {
        $event = new Events();
        $event->data = $request->all();
        $payload = $request->get('payload');
        if(is_string($payload)) {
            $event->data = json_decode($payload, true);
        } elseif (is_array($payload)) {
            $event->data = $payload;
        }
        $event->save();
        $githubService = new Github();
        $githubService->createItems($event);
        return 'ok';
    }
}
