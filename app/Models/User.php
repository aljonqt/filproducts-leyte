<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * MASS ASSIGNABLE FIELDS (VERY IMPORTANT 🔥)
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'username',
        'password',
        'role',
        'can_manage_users',
        'can_view_reports',
        'can_edit_data',
    ];

    /**
     * HIDDEN FIELDS
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * TYPE CASTING
     */
    protected $casts = [
        'can_manage_users' => 'boolean',
        'can_view_reports' => 'boolean',
        'can_edit_data' => 'boolean',
    ];

    /**
     * OPTIONAL: USE USERNAME FOR LOGIN
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }
}