<?php


namespace onethirtyone\GoogleCalendar;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\URL;
use onethirtyone\GoogleCalendar\app\GoogleClient;

class Client
{
    public $client;

    protected $accessToken;

    protected $storedToken = null;

    public function __construct()
    {
        $this->client = new Google_Client;

        $this->client->setAuthConfig(config('google-calendar.credentials_path'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setAccessType(config('google-calendar.access_type'));
        $this->client->setIncludeGrantedScopes(config('google-calendar.include_granted_scopes'));
        $this->client->setApprovalPrompt(config('google-calendar.approval_prompt', 'force'));
        $this->client->setRedirectUri(URL::to('/') . '/google/client/oauth2callback');

        $this->refreshTokenIfNeeded();
    }

    public function refreshTokenIfNeeded()
    {
        if($this->hasExistingToken()) {
            $this->client->setAccessToken($this->getExistingToken());

            if($this->client->isAccessTokenExpired()) {
                if($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    $this->updateClientWithNewToken();
                }
            }
        }
    }

    public function getExistingToken()
    {
        return GoogleClient::first()->credentials;
    }

    public function hasExistingToken()
    {
        return GoogleClient::latest()->exists();
    }

    public function authUrl()
    {
        return $this->client->createAuthUrl();
    }

    public function createClientFromAuthCode($code)
    {
        return GoogleClient::create([
            'credentials' => $this->client->fetchAccessTokenWithAuthCode($code),
        ]);
    }

    public function updateClientWithNewToken()
    {
        $client = GoogleClient::first();

        $client->update([
            'credentials' => $this->client->getAccessToken(),
        ]);
    }
}