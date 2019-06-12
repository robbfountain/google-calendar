<?php

return [
    /**
     * The Path to your Google Calendar API Credentials File
     */
    'credentials_path' => storage_path('client_secret.json'),

    'name' => 'Calendar integration',

    'redirect_uri' => '/google/client/oauth2callback',

    'access_type' => 'offline',

    'approval_prompt' => 'force',

    'scope' => Google_Service_Calendar::CALENDAR,

    'include_granted_scopes' => true,

    'redirect_route' => '/home',
];

