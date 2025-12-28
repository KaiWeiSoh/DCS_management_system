<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Deadline extends Model
{
    use HasFactory;

    protected $fillable = [
        'subject_title',
        'deadline_date',
        'description'
    ];

    protected $casts = [
        'deadline_date' => 'date',
    ];
}
