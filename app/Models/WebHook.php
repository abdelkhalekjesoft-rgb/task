<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebHook extends Model
{
    protected $table = 'webhooks_calls';

    protected $fillable = [
        'bank',
        'webhook'
    ];
}
