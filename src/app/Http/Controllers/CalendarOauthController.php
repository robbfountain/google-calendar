<?php

namespace OneThirtyOne\GoogleCalendar\app\Http\Controllers;

use Illuminate\Http\Request;
use OneThirtyOne\GoogleCalendar\Client;
use OneThirtyOne\GoogleCalendar\Channel;
use OneThirtyOne\GoogleCalendar\Calendar;
use OneThirtyOne\GoogleCalendar\App\Models\GoogleClient;

/**
 * Class CalendarOauthController
 * @package OneThirtyOne\GoogleCalendar\App\Http\Controllers
 */
class CalendarOauthController
{
    /**
     * Process callback from Google Client OAuth Request
     *
     * @param Request $request
     * @param Client  $client
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(Request $request, Client $client)
    {
        if ($client->hasExistingToken()) {
            return redirect()
                ->to(route('calendar.index'))
                ->with(['message' => 'Token already exists']);
        }

        if ($request->has('error')) {
            return redirect()
                ->to(route('calendar.index'))
                ->withErrors(['message', 'Invalid Google Authentication Attempt']);
        }

        if ($request->has('code')) {
            $client->createClientFromAuthCode($request->code);
            return redirect()->to(route('calendar.index'))
                ->with(['message' => 'Calendar linked successfully']);
        }

        return redirect()
            ->to(route('calendar.index'))
            ->withErrors(['message', 'Invalid Google Authentication Attempt']);
    }

    /**
     * Display the main integration screen
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('GoogleCalendar::calendar-index')
            ->with([
                'integrated' => GoogleClient::count(),
                'hasWebhooks' => GoogleClient::where('channel_unique_id', '!=', null)->exists(),
            ]);
    }

    /**
     * Disable the OAuth Integration
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Google_Exception
     */
    public function unRegister()
    {
        if (Calendar::hasExistingWebhooks()) {
            (new Channel())->getCurrentChannel()->stop();
        }

        GoogleClient::truncate();

        return redirect()->to(route('calendar.index'))
            ->with(['message' => 'Calendar integration removed']);
    }
}
