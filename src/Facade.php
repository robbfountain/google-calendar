<?php


namespace onethirtyone\GoogleCalendar;


class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Client';
    }
}