<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentNotification extends Model
{
    use HasFactory;

    protected $table = 'student_notifications';

    protected $fillable = ['user_id', 'message', 'read_at'];

    protected $dates = ['read_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
