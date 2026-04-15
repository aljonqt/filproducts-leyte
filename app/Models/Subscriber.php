<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $table = 'subscribers';

    protected $fillable = [
        'account_number',
        'firstname',
        'middlename',
        'lastname',
        'company_name',
        'customer_type',
        'address',
        'phone',
        'email',
        'status'
    ];

    // FULL NAME ACCESSOR
    public function getFullNameAttribute()
    {
        return trim($this->firstname . ' ' . $this->middlename . ' ' . $this->lastname);
    }
}