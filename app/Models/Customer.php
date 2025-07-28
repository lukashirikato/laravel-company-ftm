<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
    'name',
    'email',
    'phone_number',
    'program',
    'quota',
    'membership',
    'preferred_membership',
    'schedule_id',
    'user_id',
    'password',
    'birth_date',
    'schedule',
    'goals',
    'kondisi_khusus',
    'referensi',
    'pengalaman',
    'is_muslim',
    'voucher_code',
];


    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'agree' => 'boolean',
        'quota' => 'integer',
        'email_verified_at' => 'datetime',
        'is_verified' => 'boolean',
        'force_password_change' => 'boolean',
    ];

    /**
     * Relasi many-to-many: Customer bisa punya banyak jadwal
     */
public function schedules()
{
    return $this->belongsToMany(Schedule::class, 'customer_schedules'); // pastikan pivot table benar
}
}
