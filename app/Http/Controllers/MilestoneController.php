<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\Milestone;

class MilestoneController extends Controller
{
    /**
     * Called when user clicks Milestones on dashboard.
     * Sets a session flag and redirects to the milestones index.
     */
    public function enter(Request $request)
    {
        $request->session()->put('allow_milestones', true);
        return redirect()->route('milestones.index');
    }

    /**
     * Show the milestones page only if session flag is present.
     */
    public function index(Request $request)
    {
        if (! $request->session()->pull('allow_milestones')) {
            // Not allowed, redirect back to dashboard
            return redirect()->route('dashboard')->with('error', 'Please open Milestones from the Dashboard.');
        }

        $user = Auth::user();
        $project = $user->projects()->with('milestones')->first();

        $milestones = $project ? $project->milestones()->orderBy('due_at')->get() : collect();

        return view('milestones.index', compact('milestones'));
    }

    // Show create milestone form
    public function create(Request $request)
    {
        // Must come from dashboard flow
        if (! $request->session()->get('allow_milestones')) {
            // still allow direct creation if authenticated and project exists
            // but prefer user to open from dashboard
        }

        $user = Auth::user();
        $project = $user->projects()->first();
        if (! $project) {
            return redirect()->route('milestones.index')->with('error', 'Please register a project first.');
        }

        return view('milestones.create', compact('project'));
    }

    // Store a new milestone
    public function store(Request $request)
    {
        $user = Auth::user();
        $project = $user->projects()->first();
        if (! $project) {
            return redirect()->route('milestones.index')->with('error', 'Please register a project first.');
        }

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'description' => ['nullable','string','max:2000'],
            'start_at' => ['nullable','date'],
            'end_at' => ['nullable','date'],
            'priority' => ['nullable','integer'],
        ]);

        // Validate chronological order if both provided
        if (!empty($data['start_at']) && !empty($data['end_at'])) {
            if (strtotime($data['start_at']) > strtotime($data['end_at'])) {
                return back()->withErrors(['end_at' => 'End time must be after start time.'])->withInput();
            }
        }

        $m = new Milestone();
        $m->project_id = $project->id;
        $m->title = $data['title'];
        $m->description = $data['description'] ?? null;
    // Store start and end explicitly
    $m->start_at = $data['start_at'] ?? null;
    $m->due_at = $data['end_at'] ?? null;
    $m->priority = $data['priority'] ?? 0;
        $m->save();

        return redirect()->route('milestones.index')->with('success', 'Milestone created.');
    }
}
