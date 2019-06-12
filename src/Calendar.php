<?php


namespace onethirtyone\GoogleCalendar;


use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\URL;
use onethirtyone\GoogleCalendar\classes\Client;

class Calendar
{
    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName('Calendar integration');
        $this->client->setAuthConfig(config('google-calendar.credentials_path'));
        $this->client->setAccessType("offline");
        $this->client->setIncludeGrantedScopes(true);
        $this->client->setApprovalPrompt('force');
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setRedirectUri(route('calendar.oauth.callback'));
    }

    public function authUrl()
    {
        return $this->client->createAuthUrl();
    }
}