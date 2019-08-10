<?php

namespace onethirtyone\GoogleCalendar\App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class GoogleClient
 * @package onethirtyone\GoogleCalendar\App
 */
class GoogleClient extends Model
{
    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'credentials' => 'array',
    ];
}

