<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'student_id',
        'meeting_date',
        'meeting_time',
        'purpose',
        'description'
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'meeting_time' => 'datetime',
    ];

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
