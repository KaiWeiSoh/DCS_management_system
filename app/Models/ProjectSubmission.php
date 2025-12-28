<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'abstract',
        'extended_abstract',
        'report',
        'source_code',
        'presentation_video',
        'submitted_at',
        'feedback',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the project that owns this submission.
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
