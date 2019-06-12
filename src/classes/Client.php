<?php


namespace onethirtyone\GoogleCalendar\classes;


use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\URL;
use onethirtyone\GoogleCalendar\app\GoogleClient;

class Client
{
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
        $this->client->setRedirectUri(URL::to('/') . '/calendar/oauth2callback');

    }

    public function authUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function setAccessTokenFromAuthCode($code)
    {
        $this->accessToken =  $this->client->fetchAccessTokenWithAuthCode($code);
    }

    public function createClientFromAuthCode($code)
    {
        $this->setAccessTokenFromAuthCode($code);

        return GoogleClient::create($this->accessToken);
    }
}