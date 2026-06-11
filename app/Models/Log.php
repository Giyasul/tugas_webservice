<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    protected $table = 'logs';

    protected $fillable = [
        'user_id',
        'method',
        'endpoint',
        'status_code',
        'ip_address',
        'request_body',
        'response_body',
    ];
}
