<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Schedule extends Model
{
    use HasFactory;

    // Optional: jika nama tabel bukan jamak dari model
    // protected $table = 'schedules';

    protected $fillable = [
        'class_name',
        'day',
        'class_time',
        'instructor',
        'show_on_landing',
        
    ];

    protected $casts = [
        'class_time' => 'datetime:H:i',
    ];

public function customers()
{
    return $this->belongsToMany(Customer::class, 'customer_schedule')->withTimestamps();
}

}
