<?php

namespace OneThirtyOne\GoogleCalendar\app\Http\Controllers;

use Google_Exception;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\URL;
use Illuminate\Http\RedirectResponse;
use OneThirtyOne\GoogleCalendar\Channel;
use Illuminate\Contracts\Routing\ResponseFactory;
use OneThirtyOne\GoogleCalendar\App\Events\GoogleCalendarWebhookFired;

/**
 * Class CalendarWebhookController
 * @package OneThirtyOne\GoogleCalendar\App\Http\Controllers
 */
class CalendarWebhookController
{

    /**
     * @var Channel
     */
    protected $channel;

    /**
     * @return RedirectResponse
     * @throws Google_Exception
     */
    public function register()
    {
        $channel = new Channel();
        $channel->id = Uuid::uuid4();
        $channel->type = 'web_hook';
        $channel->address = Url::to('/') . '/google/calendar/webhook';
        $channel->save();

        return redirect()
            ->to(route('calendar.index'))
            ->with(['message' => 'Webhooks Enabled for Calendar']);
    }

    /**
     * @return RedirectResponse
     * @throws Google_Exception
     */
    public function unRegister()
    {
        $channel = new Channel();
        $channel->getCurrentChannel()->stop();

        return redirect()
            ->to(route('calendar.index'))
            ->with(['message' => 'Webhooks Disabled for Calendar']);

    }

    /**
     * @param Request $request
     *
     * @return ResponseFactory|Response
     */
    public function handle(Request $request)
    {
        event(new GoogleCalendarWebhookFired($request));

        return response([], 200);
    }

}
