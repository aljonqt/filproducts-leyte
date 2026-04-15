<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilbizInquiry extends Model
{
    protected $table = 'filbiz_inquiry';
    
    public $timestamps = false;

    protected $fillable = [
        'company_name',
        'filbiz_nature_of_business',
        'office_address',

        'firstname',
        'middlename',
        'lastname',
        'mobile_number',
        'email',

        'landline',
        'position',
        'company_contact_person',

        'nature_of_business',
        'monthly_subscription',

        'signature',
        'file',

        'status' // 🔥 IMPORTANT (add this column if not yet)
    ];
}