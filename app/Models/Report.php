<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['project_id', 'title', 'submitted_at', 'file_path'];

    protected $dates = ['submitted_at'];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
