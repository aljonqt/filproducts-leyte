<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'applications';

    protected $fillable = [
        'firstname',
        'lastname',
        'city',
        'monthly_subscription',
        'file',
        'type'
    ];
}