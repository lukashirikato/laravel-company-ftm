<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['customer_id', 'schedule_date', 'schedule_time','program',];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}