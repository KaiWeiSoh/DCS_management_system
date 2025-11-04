<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Report;
use App\Models\Milestone;
use App\Models\StudentNotification;

class StudentDashboardController extends Controller
{
    /**
     * Show the student dashboard for authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Load the user's project (assume one project per student)
        $project = $user->projects()->with(['reports', 'milestones'])->first();

        // If no project, return empty defaults
        if (! $project) {
            $fyp = ['title' => 'No project yet', 'progress' => 0, 'supervisor' => ''];
            $reports = collect();
            $milestones = collect();
        } else {
            $fyp = ['title' => $project->title, 'progress' => $project->progress, 'supervisor' => $project->supervisor];
            $reports = $project->reports()->orderByDesc('submitted_at')->get();
            $milestones = $project->milestones()->orderBy('due_at')->get();
        }

        $notifications = $user->notificationsCustom()->orderByDesc('created_at')->limit(10)->get();

        return view('dashboard', compact('notifications', 'fyp', 'reports', 'milestones'));
    }
}
