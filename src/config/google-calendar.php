<?php

return [
    /**
     * The Path to your Google Calendar API Credentials File
     */
    'credentials_path' => storage_path('client_secret.json'),

    /**
     * The path where users will be redirected to after they authorize google.
     */
    'redirect_route' => '/home',

    /**
     * Permitted Users
     *
     * This setting will control the access to your calendar integration dashboard
     * By default all users will have access while in local or development All other access will be controlled
     */
    'permitted_users' => [
        // your@email.com
    ]
];

