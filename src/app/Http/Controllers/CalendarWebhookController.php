<?php

namespace onethirtyone\GoogleCalendar\app\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use onethirtyone\GoogleCalendar\Channel;
use onethirtyone\GoogleCalendar\GoogleCalendarFactory;
use Ramsey\Uuid\Uuid;

class CalendarWebhookController
{

    protected $channel;

    public function register()
    {
        $channel = new Channel();
        $channel->id = Uuid::uuid4();
        $channel->type = 'web_hook';
        $channel->address = 'https://dev.131studios.com/google/calendar/webhook';
        $channel->save();

        return redirect()->to(route('calendar.index'))->with(['message' => 'Webhooks Enabled for Calendar']);
    }

    public function handle(Request $request)
    {
        Log::info('Webhook Received ' . $request->header('x-goog-resource-state') . ' id ' . $request->header('x-goog-resource-id'));
        Log::info('uri ' . $request->header('x-goog-resource-uri'));
        Log::info('channel ' . $request->header('x-goog-channel-id'));

        return response([],200);
    }

}