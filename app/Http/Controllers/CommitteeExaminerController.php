<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\StudentNotification;

class CommitteeExaminerController extends Controller
{
    /**
     * Display the list of projects with option to assign examiners.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        // Get all approved projects with their students, supervisors, and examiners
        // Only include FYP II and Project students (exclude FYP I)
        $projects = Project::where('approved', true)
            ->with(['user', 'supervisorUser'])
            ->whereHas('user', function($query) {
                $query->whereIn('project', ['FYP II', 'Project']);
            })
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all supervisors (users with supervisor role)
        $supervisors = User::where('role', 'supervisor')->orderBy('name')->get();

        return view('committee.examiners.index', compact('projects', 'supervisors'));
    }

    /**
     * Assign an examiner to a project.
     */
    public function assign(Request $request, $projectId)
    {
        $user = Auth::user();
        
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $request->validate([
            'examiner_id' => ['required', 'exists:users,id'],
        ]);

        $project = Project::findOrFail($projectId);
        
        // Check if trying to assign the same supervisor as examiner
        if ($project->supervisor_id == $request->examiner_id) {
            return back()->with('error', 'Examiner cannot be the same as the supervisor.');
        }

        $project->examiner_id = $request->examiner_id;
        $project->save();

        // Notify the examiner
        StudentNotification::create([
            'user_id' => $request->examiner_id,
            'message' => 'You have been assigned as an examiner for the project: ' . $project->title . ' (Student: ' . $project->user->name . ')',
            'type' => 'examiner_assignment',
        ]);

        return back()->with('success', 'Examiner assigned successfully.');
    }
}
