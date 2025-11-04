<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Milestone extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'description', 'due_at', 'start_at', 'priority'];

    protected $casts = [
        'due_at' => 'datetime',
        'start_at' => 'datetime',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // no duplicate properties; start_at and priority are included above
}
