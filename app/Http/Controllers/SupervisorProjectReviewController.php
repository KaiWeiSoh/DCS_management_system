<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectSubmission;

class SupervisorProjectReviewController extends Controller
{
    /**
     * Display the list of supervised projects with their submissions.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all projects supervised by this user along with their submissions
        $projects = Project::where('supervisor_id', $user->id)
            ->with(['user', 'submission'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Also get projects where user is examiner
        $examinerProjects = Project::where('examiner_id', $user->id)
            ->with(['user', 'submission'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('supervisor.project-review.index', compact('projects', 'examinerProjects'));
    }

    /**
     * Show detailed view of a specific project submission.
     */
    public function show($projectId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $project = Project::where(function($query) use ($user) {
                $query->where('supervisor_id', $user->id)
                      ->orWhere('examiner_id', $user->id);
            })
            ->with(['user', 'submission'])
            ->findOrFail($projectId);

        return view('supervisor.project-review.show', compact('project'));
    }
}
