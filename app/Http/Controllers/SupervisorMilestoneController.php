<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Models\StudentNotification;

class SupervisorMilestoneController extends Controller
{
    /**
     * Display tasks for all supervised students.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all tasks created by this supervisor with student information
        $tasks = Task::where('supervisor_id', $user->id)
            ->with('student')
            ->orderByDesc('created_at')
            ->get();

        return view('supervisor.milestones.index', compact('tasks'));
    }

    /**
     * Show form to create a new task.
     */
    public function create()
    {
        $user = Auth::user();
        
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all students supervised by this user
        $students = Project::where('supervisor_id', $user->id)
            ->with('user')
            ->get()
            ->pluck('user')
            ->unique('id');

        return view('supervisor.milestones.create', compact('students'));
    }

    /**
     * Store a newly created task.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
        ]);

        $validated['supervisor_id'] = $user->id;
        $validated['status'] = 'pending';

        $task = Task::create($validated);

        // Send notification to student
        $student = User::find($validated['student_id']);
        StudentNotification::create([
            'user_id' => $student->id,
            'message' => "New task assigned by {$user->name}: {$task->title}"
        ]);

        return redirect()->route('supervisor.milestones.index')
            ->with('success', 'Task created successfully!');
    }

    /**
     * Show task details with all assigned students and their submissions.
     */
    public function view($id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $task = Task::where('id', $id)
            ->where('supervisor_id', $user->id)
            ->with('student')
            ->firstOrFail();

        return view('supervisor.milestones.view', compact('task'));
    }
}
