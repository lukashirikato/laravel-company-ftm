<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $table = 'feedbacks'; // <--- tambahkan baris ini

    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
    ];
}