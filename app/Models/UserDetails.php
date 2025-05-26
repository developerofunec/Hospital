<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class UserDetails extends Authenticatable
{
    use HasFactory;
    use HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'date_of_birth',
        'fin_code',
        'email',
        'phone_number',
        'address',
        'gender',
        'password'
    ];


    protected $hidden = [
        'password',
        'created_at',
        'updated_at'
    ];
}
