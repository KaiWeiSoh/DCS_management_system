<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\StudentNotification;

class SupervisorMeetingController extends Controller
{
    /**
     * Display a listing of meetings for the supervisor.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all meetings created by this supervisor
        $meetings = Meeting::where('supervisor_id', $user->id)
            ->with('student')
            ->orderBy('meeting_date', 'desc')
            ->orderBy('meeting_time', 'desc')
            ->get();

        return view('supervisor.meetings.index', compact('meetings'));
    }

    /**
     * Show the form for creating a new meeting.
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
            ->pluck('user.name', 'user.id')
            ->unique();

        return view('supervisor.meetings.create', compact('students'));
    }

    /**
     * Store a newly created meeting in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'meeting_date' => 'required|date|after_or_equal:today',
            'meeting_time' => 'required',
            'purpose' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['supervisor_id'] = $user->id;

        $meeting = Meeting::create($validated);

        // Create notification for the student
        StudentNotification::create([
            'user_id' => $validated['student_id'],
            'message' => "New meeting scheduled with {$user->name} on " . 
                        \Carbon\Carbon::parse($validated['meeting_date'])->format('d M Y') . 
                        " at " . \Carbon\Carbon::parse($validated['meeting_time'])->format('h:i A') . 
                        ". Purpose: {$validated['purpose']}"
        ]);

        return redirect()->route('supervisor.meetings.index')
            ->with('success', 'Meeting created successfully!');
    }
}
