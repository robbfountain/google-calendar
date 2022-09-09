<?php


namespace OneThirtyOne\GoogleCalendar;

use Google_Client;
use Google_Service_Calendar;
use Illuminate\Support\Facades\URL;
use OneThirtyOne\GoogleCalendar\App\Models\GoogleClient;

/**
 * Class Client
 * @package OneThirtyOne\GoogleCalendar
 */
class Client
{
    /**
     * @var Google_Client
     */
    public $client;

    /**
     * @var
     */
    protected $accessToken;

    /**
     * @var null
     */
    protected $storedToken = null;

    /**
     * Client constructor.
     * @throws \Google_Exception
     */
    public function __construct()
    {
        $this->client = new Google_Client;

        $this->client->setAuthConfig(config('google-calendar.credentials_path'));
        $this->client->addScope(Google_Service_Calendar::CALENDAR);
        $this->client->setAccessType(config('google-calendar.access_type'));
        $this->client->setIncludeGrantedScopes(config('google-calendar.include_granted_scopes'));
        $this->client->setApprovalPrompt(config('google-calendar.approval_prompt', 'force'));
        $this->client->setRedirectUri(URL::to('/') . '/google/calendar/oauth2callback');

        $this->refreshTokenIfNeeded();
    }

    /**
     * Refresh the auth token if it's needed
     */
    public function refreshTokenIfNeeded()
    {
        if ($this->hasExistingToken()) {
            $this->client->setAccessToken($this->getExistingToken());

            if ($this->client->isAccessTokenExpired()) {
                if ($this->client->getRefreshToken()) {
                    $this->client->fetchAccessTokenWithRefreshToken(
                        $this->client->getRefreshToken()
                    );
                    $this->updateClientWithNewToken();
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function hasExistingToken()
    {
        return GoogleClient::latest()->exists();
    }

    /**
     * @return mixed
     */
    public function getExistingToken()
    {
        return GoogleClient::first()->credentials;
    }

    /**
     *
     */
    public function updateClientWithNewToken()
    {
        static::updateClientWithFreshData([
            'credentials' => $this->client->getAccessToken(),
        ]);
    }

    /**
     * @param array $attributes
     */
    public static function updateClientWithFreshData(array $attributes)
    {
        $client = GoogleClient::first();

        $client->update($attributes);
    }

    /**
     * @return bool
     */
    public static function hasSyncToken()
    {
        return GoogleClient::first()->sync_token != null;
    }

    /**
     * @return mixed
     */
    public static function getSyncToken()
    {
        return GoogleClient::first()->sync_token;
    }

    /**
     * @param array $attributes
     */
    public static function updateClientWithChannel(array $attributes)
    {
        static::updateClientWithFreshData($attributes);
    }

    /**
     * @param string|null $token
     */
    public static function updateClientWithSyncToken(string $token = null)
    {
        static::updateClientWithFreshData(['sync_token' => $token]);
    }

    /**
     * @return string
     */
    public function authUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * @param $code
     *
     * @return mixed
     */
    public function createClientFromAuthCode($code)
    {
        return GoogleClient::create([
            'credentials' => $this->client->fetchAccessTokenWithAuthCode($code),
        ]);
    }

}
