<?php


namespace onethirtyone\GoogleCalendar;

use Google_Service_Calendar;

class GoogleCalendarFactory
{

    public static function getInstanceWithCalendarId($calendarId)
    {
        $client = static::getAuthenticatedClientInstance();

        $calendar = new Google_Service_Calendar($client);

        return new Calendar($calendar, $calendarId);

    }

    public static function getAuthenticatedClientInstance()
    {
        $googleClient = new Client;

        return $googleClient->client;
    }
}