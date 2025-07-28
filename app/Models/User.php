<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // ⬅️ Pastikan ditambahkan jika kamu pakai kolom 'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
