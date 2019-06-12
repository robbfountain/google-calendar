<?php


namespace onethirtyone\GoogleCalendar\Http\Traits;

trait UsesGoogleCalendar
{
    public function updateGoogleCredentials($credentials)
    {
        return $this->update($credentials);
    }

}