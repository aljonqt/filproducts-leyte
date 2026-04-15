<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoverageArea extends Model
{
    protected $table = 'coverage_areas';

    protected $fillable = [
        'barangay',
        'area_name',
        'city',
        'province',
        'isavailable'
    ];
}