<?php

namespace onethirtyone\GoogleCalendar\App;

use Illuminate\Database\Eloquent\Model;

class GoogleClient extends Model
{
    protected $guarded = [];

    protected $casts = [
        'credentials' => 'array',
    ];
}

