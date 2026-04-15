<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    protected $table = 'area';

    protected $fillable = [
        'area_name',
        'team_leader',
    ];

    /**
     * =========================
     * RELATIONSHIP
     * =========================
     * One Area has many Complaints
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'area', 'area_name');
    }
}