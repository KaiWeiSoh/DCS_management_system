<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\User;
use App\Models\StudentNotification;

class ProjectController extends Controller
{
    // Show the FYP page for the authenticated student
    public function index()
    {
        $user = Auth::user();
        // ensure only students access
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }

        $project = $user->projects()->with('reports')->first();

        return view('fyp.index', compact('project'));
    }

    // Show create project form
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }

        // list supervisors
        $supervisors = User::where('role', 'supervisor')->get();
        return view('projects.create', compact('supervisors'));
    }

    // Store new project
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'supervisor_id' => ['required', 'integer'],
        ]);

        $supervisor = User::find($data['supervisor_id']);
        $supervisorName = $supervisor ? $supervisor->name : null;

        $project = Project::create([
            'user_id' => $user->id,
            'title' => $data['title'],
            'progress' => 0,
            'supervisor' => $supervisorName,
            'supervisor_id' => $supervisor ? $supervisor->id : null,
            'approved' => null,
        ]);

        // Notify the chosen supervisor (if exists) that a student has registered a project
        if ($supervisor) {
            StudentNotification::create([
                'user_id' => $supervisor->id,
                'message' => 'Student "' . $user->name . '" has registered a project titled "' . $project->title . '" and requested you as supervisor. Please review.',
            ]);
        }

        return redirect()->route('fyp.index')->with('success', 'Project registered and sent for approval.');
    }
}
