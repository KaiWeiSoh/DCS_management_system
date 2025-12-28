<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $casts = [
        'approved' => 'boolean',
        'approved_at' => 'datetime',
    ];
    protected $fillable = ['user_id', 'title', 'progress', 'supervisor', 'supervisor_id', 'examiner_id', 'approved', 'approved_at', 'approved_by'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    public function milestones()
    {
        return $this->hasMany(Milestone::class);
    }

    public function supervisorUser()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function examinerUser()
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }

    public function finalGrade()
    {
        return $this->hasOne(FinalGrade::class);
    }

    public function submission()
    {
        return $this->hasOne(ProjectSubmission::class);
    }
}
