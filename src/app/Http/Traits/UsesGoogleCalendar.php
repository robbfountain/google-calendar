<?php


namespace onethirtyone\GoogleCalendar\app\Http\Traits;

trait UsesGoogleCalendar
{
    public function updateGoogleCredentials($credentials)
    {
        return $this->update($credentials);
    }

}