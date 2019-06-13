<?php

namespace onethirtyone\GoogleCalendar;

use Google_Service_Calendar;

class Calendar
{
    protected $client;
    protected $calendar;
    protected $calendarId = 'primary';
    protected $optParams = [
        'orderBy'      => 'startTime',
        'singleEvents' => true,
    ];

    /**
     * Calendar constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->calendar =  new Google_Service_Calendar(Client::fetch());

    }

    public function calendar($id)
    {
        $this->calendarId = $id;

        return $this;
    }

    public function listEvents($options = [])
    {
        $results = $this->calendar->events->listEvents($this->calendarId, array_merge($this->optParams, $options));
        return $results->getItems();
    }
}