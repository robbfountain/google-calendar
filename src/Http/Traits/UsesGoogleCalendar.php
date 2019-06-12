<?php


namespace onethirtyone\GoogleCalendar\Http\Traits;

trait UsesGoogleCalendar
{
    public function updateCredentials($credentials)
    {
        return $this->update($credentials);
    }

}