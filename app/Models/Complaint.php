<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Complaint extends Model
{
    protected $table = 'complaints';

    protected $fillable = [
        'ticket_number',
        'account_name',
        'account_number',
        'email',
        'address',
        'area',
        'mobile_number',
        'category',
        'status',
        'remarks'
    ];

    public $timestamps = false;
}