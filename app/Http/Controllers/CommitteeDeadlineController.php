<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Deadline;
use App\Models\StudentNotification;
use App\Models\User;

class CommitteeDeadlineController extends Controller
{
    /**
     * Display a listing of deadlines.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $deadlines = Deadline::orderBy('deadline_date', 'asc')->get();

        return view('committee.deadlines.index', compact('deadlines'));
    }

    /**
     * Show the form for creating a new deadline.
     */
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        return view('committee.deadlines.create');
    }

    /**
     * Store a newly created deadline in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'subject_title' => 'required|string|max:255',
            'deadline_date' => 'required|date|after_or_equal:today',
            'description' => 'nullable|string',
        ]);

        $deadline = Deadline::create($validated);

        // Create notifications for all students and supervisors
        $notificationMessage = "New deadline: {$validated['subject_title']} - Due on " . 
                              \Carbon\Carbon::parse($validated['deadline_date'])->format('d M Y');
        
        if (!empty($validated['description'])) {
            $notificationMessage .= ". " . $validated['description'];
        }

        // Get all students and supervisors
        $users = User::whereIn('role', ['student', 'supervisor'])->get();
        
        foreach ($users as $targetUser) {
            StudentNotification::create([
                'user_id' => $targetUser->id,
                'message' => $notificationMessage
            ]);
        }

        return redirect()->route('committee.deadlines.index')
            ->with('success', 'Deadline created successfully and notifications sent!');
    }
}
