<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Report;
use App\Models\Milestone;
use App\Models\StudentNotification;
use App\Models\Task;

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
            $milestones = collect();
        } else {
            $fyp = ['title' => $project->title, 'progress' => $project->progress, 'supervisor' => $project->supervisor];
            $milestones = $project->milestones()->orderBy('due_at')->get();
        }

        // Get task statistics for the student
        $tasks = Task::where('student_id', $user->id)->get();
        $taskStats = [
            'total' => $tasks->count(),
            'submitted' => $tasks->whereNotNull('submitted_at')->count(),
            'pending' => $tasks->whereNull('submitted_at')->count(),
            'with_feedback' => $tasks->whereNotNull('feedback')->count(),
        ];

        $notifications = $user->notificationsCustom()->orderByDesc('created_at')->limit(2)->get();
        $totalNotifications = $user->notificationsCustom()->count();

        return view('dashboard', compact('notifications', 'fyp', 'taskStats', 'milestones', 'totalNotifications'));
    }
}
