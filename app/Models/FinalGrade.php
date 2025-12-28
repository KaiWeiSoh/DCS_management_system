<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinalGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'supervisor_id',
        'final_grade',
        'supervisor_grade',
        'supervisor_rubric_data',
        'supervisor_comments',
        'supervisor_graded_at',
        'examiner_grade',
        'examiner_rubric_data',
        'examiner_comments',
        'examiner_graded_at',
        'rubric_data',
        'comments',
        'report_file',
        'finalized_at',
        'finalized_by',
        'status'
    ];

    protected $casts = [
        'final_grade' => 'decimal:2',
        'supervisor_grade' => 'decimal:2',
        'examiner_grade' => 'decimal:2',
        'supervisor_rubric_data' => 'array',
        'examiner_rubric_data' => 'array',
        'rubric_data' => 'array',
        'supervisor_graded_at' => 'datetime',
        'examiner_graded_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }
}
