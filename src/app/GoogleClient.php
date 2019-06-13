<?php

namespace onethirtyone\GoogleCalendar\app;

use Illuminate\Database\Eloquent\Model;

class GoogleClient extends Model
{
    protected $guarded = [];

    protected $casts = [
        'credentials' => 'array',
    ];
}

