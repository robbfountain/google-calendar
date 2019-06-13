<?php

namespace onethirtyone\GoogleCalendar;

use Google_Service_Calendar_Event;

class Event
{
    /**
     * @var \Google_Service_Calendar_Event
     */
    public $googleEvent;

    public function __construct()
    {
        $this->googleEvent = new Google_Service_Calendar_Event;
    }

}