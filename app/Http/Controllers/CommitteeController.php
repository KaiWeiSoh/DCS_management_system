<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Report;
use App\Models\StudentNotification;

class CommitteeController extends Controller
{
    // List pending proposals
    public function proposals()
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        // Show submitted reports from students: title and submitted date
        $reports = Report::with(['project.user'])->orderByDesc('submitted_at')->get();
        return view('committee.proposals.index', compact('reports'));
    }

    // Show project approval page (list of registered project titles pending approval)
    public function approvals()
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        // Load all projects so committee can see approved / rejected / pending with status
        $projects = Project::with(['user', 'supervisorUser'])->orderByDesc('created_at')->get();
        return view('committee.approvals.index', compact('projects'));
    }

    // Approve a proposal
    public function approve($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::findOrFail($id);
        $project->approved = true;
        $project->approved_at = now();
        $project->approved_by = $user->id;
        $project->save();

        // Notify student
        StudentNotification::create([
            'user_id' => $project->user_id,
            'message' => 'Your project "' . $project->title . '" has been approved by the committee.',
        ]);

    return redirect()->route('committee.approvals.index')->with('success', 'Project approved.');
    }

    // Reject a proposal
    public function reject($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::findOrFail($id);
        $project->approved = false;
        $project->approved_at = now();
        $project->approved_by = $user->id;
        $project->save();

        StudentNotification::create([
            'user_id' => $project->user_id,
            'message' => 'Your project "' . $project->title . '" has been refused by the committee.',
        ]);

    return redirect()->route('committee.approvals.index')->with('success', 'Project refused.');
    }
}
