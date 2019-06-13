<?php

namespace onethirtyone\GoogleCalendar;

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
}