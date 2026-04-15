<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResidentialInquiry extends Model
{
    protected $table = 'residential_inquiry';

    public $timestamps = false;

    protected $fillable = [
        'salutation',
        'gender',
        'birthday',
        'civilstatus',
        'citizenship',
        'firstname',
        'middlename',
        'lastname',
        'mobile_number',
        'tin_no',
        'email',
        'home_tel_no',
        'gsis_sss_no',

        'mother_firstname',
        'mother_middlename',
        'mother_lastname',

        'home_ownership',
        'year_of_stay',

        'street',
        'brgy',
        'city',
        'zip_code',

        'employer',
        'employment_street',
        'employment_barangay',
        'employment_city',
        'employment_zipcode',
        'employment_tel_no',

        'years_in_company',
        'current_position',
        'monthly_income',
        'authorized_firstname',
        'authorized_middlename',
        'authorized_lastname',
        'authorized_relation',
        'authorized_contact_number',
        'monthly_subscription',
        'signature',
        'latitude',
        'longitude',
        'created_at',
        'file',
    


        'status'
    ];
}