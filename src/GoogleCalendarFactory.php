<?php


namespace onethirtyone\GoogleCalendar;

use Google_Service_Calendar;

/**
 * Class GoogleCalendarFactory
 * @package onethirtyone\GoogleCalendar
 */
class GoogleCalendarFactory
{

    /**
     * @param $calendarId
     *
     * @return Calendar
     * @throws \Google_Exception
     */
    public static function getInstanceWithCalendarId($calendarId)
    {
        $client = static::getAuthenticatedClientInstance();

        $calendar = new Google_Service_Calendar($client);

        return new Calendar($calendar, $calendarId);

    }

    /**
     * @return \Google_Client
     * @throws \Google_Exception
     */
    public static function getAuthenticatedClientInstance()
    {
        $googleClient = new Client;

        return $googleClient->client;
    }
}