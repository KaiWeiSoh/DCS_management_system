<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'supervisor_id',
        'student_id',
        'title',
        'description',
        'due_date',
        'status',
        'priority',
        'submission_file',
        'submitted_at',
        'feedback'
    ];

    protected $casts = [
        'due_date' => 'date',
        'submitted_at' => 'datetime',
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
