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
        // 'your@email.com'
    ],

    /**
     * Permitted Permission Groups
     *
     * The groups that are permitted to link a google calendar
     */
    'permitted_groups' => [
        // 'Administrators',
    ],

    /**
     * Middleware
     *
     * This setting controls the middleware used for determining who can link
     * a google calendar and enable webhooks
     */
    'middleware' => [
        'web',
        onethirtyone\GoogleCalendar\App\Http\Middleware\CalendarAuth::class,
    ],
];

