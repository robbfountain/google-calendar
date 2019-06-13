<?php


namespace onethirtyone\GoogleCalendar;

use Google_Client;
use Illuminate\Support\Facades\URL;
use onethirtyone\GoogleCalendar\app\GoogleClient;

class Client
{
    protected $client;

    protected $accessToken;

    protected $storedToken = null;

    public function __construct()
    {
        $this->client = new Google_Client();
        $this->client->setApplicationName(config('google-calendar.name'));
        $this->client->setAuthConfig(config('google-calendar.credentials_path'));
        $this->client->setAccessType(config('google-calendar.access_type'));
        $this->client->setIncludeGrantedScopes(config('google-calendar.include_granted_scopes'));
        $this->client->setApprovalPrompt(config('google-calendar.approval_prompt', 'force'));
        $this->client->addScope(config('google-calendar.scope'));
        $this->client->setRedirectUri(URL::to('/') . '/google/client/oauth2callback');
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

    public function getAccessToken()
    {
        if ($this->hasExistingToken()) {

            $token = GoogleClient::first();
            $this->client->setAccessToken($token->credentials);

            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                    $this->updateClientWithNewToken();
                }
            }
        }

        return $this->client->getAccessToken();
    }

    public function updateClientWithNewToken()
    {
        $client = GoogleClient::first();

        $client->update([
            'credentials' => $this->client->getAccessToken()
        ]);
    }

    public function token()
    {
        $this->getAccessToken();

        return $this->client;
    }

    public function setAccessToken($token = null)
    {
        if ($token) {
            $this->client->setAccessToken($token);
        } else if ($this->hasExistingToken()) {
            $this->client->setAccessToken($this->storedToken);
        }

        return $this;
    }

    public function hasExistingToken()
    {
        return GoogleClient::latest()->exists();
    }

    public function fetch()
    {
        return $this->client;
    }
}