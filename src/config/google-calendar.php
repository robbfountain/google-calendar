<?php

return [
    /**
     * The Path to your Google Calendar API Credentials File
     */
    'credentials_path' => storage_path('client_secret.json'),

    'name' => 'Calendar integration',

    /**
     * The path where users will be redirected to after they authorize google.
     */
    'redirect_route' => '/home',
];

