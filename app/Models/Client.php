<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    public $table = 'client';
    public $timestamps = false;
    protected $fillable = [
        'id',
        'apelnomb',
        'location',
        'post_code',
        'phonenumber',

    ];
}
