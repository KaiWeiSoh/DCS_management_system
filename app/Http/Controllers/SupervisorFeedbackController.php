<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\StudentNotification;

class SupervisorFeedbackController extends Controller
{
    /**
     * Display a listing of submitted tasks from supervised students.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all task submissions from students supervised by this user
        $submissions = Task::where('supervisor_id', $user->id)
            ->whereNotNull('submitted_at')
            ->with(['student'])
            ->orderByDesc('submitted_at')
            ->get();

        return view('supervisor.feedback.index', compact('submissions'));
    }

    /**
     * Store feedback for a specific task submission.
     */
    public function store(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $task = Task::where('supervisor_id', $user->id)
            ->whereNotNull('submitted_at')
            ->findOrFail($id);

        $validated = $request->validate([
            'feedback' => 'required|string',
        ]);

        $task->update([
            'feedback' => $validated['feedback']
        ]);

        // Notify student
        StudentNotification::create([
            'user_id' => $task->student_id,
            'message' => 'Your supervisor has provided feedback on your task submission: ' . $task->title,
            'type' => 'feedback'
        ]);

        return redirect()->route('supervisor.feedback.index')
            ->with('success', 'Feedback provided successfully!');
    }
}
