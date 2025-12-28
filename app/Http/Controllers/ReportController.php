<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Project;

class ReportController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }
        $project = $user->projects()->first();
        if (!$project) {
            return redirect()->route('fyp.index')->with('error', 'Register a project before submitting a report.');
        }
        return view('reports.create', compact('project'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }
        $project = $user->projects()->first();
        if (!$project) {
            return redirect()->route('fyp.index')->with('error', 'Register a project before submitting a report.');
        }

        $data = $request->validate([
            'title' => ['required','string','max:255'],
            'file' => ['nullable','file','max:5120'], // max 5MB
        ]);

        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('reports','public');
        }

        Report::create([
            'project_id' => $project->id,
            'title' => $data['title'],
            'submitted_at' => now(),
            'file_path' => $filePath,
        ]);

        return redirect()->route('fyp.index')->with('success', 'Report submitted successfully.');
    }

    public function viewFeedback($id)
    {
        $user = Auth::user();
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }

        $report = Report::with('project')
            ->whereHas('project', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->findOrFail($id);

        return view('reports.feedback', compact('report'));
    }
}
