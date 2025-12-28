<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Report;
use App\Models\Project;
use App\Models\FinalReport;

class SupervisorReportController extends Controller
{
    // List all final reports from supervised students
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all final reports from supervised students
        $supervisedStudentIds = Project::where('supervisor_id', $user->id)
            ->pluck('user_id')
            ->toArray();

        $finalReports = FinalReport::whereIn('student_id', $supervisedStudentIds)
            ->with('student')
            ->orderByDesc('submitted_at')
            ->get();

        return view('supervisor.reports.index', compact('finalReports'));
    }

    // Show grading form for a specific report
    public function grade($id)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $report = Report::with(['project.user'])
            ->whereHas('project', function ($q) use ($user) {
                $q->where('supervisor_id', $user->id);
            })
            ->findOrFail($id);

        return view('supervisor.reports.grade', compact('report'));
    }

    // Store the grade for a report
    public function storeGrade(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $report = Report::whereHas('project', function ($q) use ($user) {
            $q->where('supervisor_id', $user->id);
        })->findOrFail($id);

        $data = $request->validate([
            'marks' => ['required', 'numeric', 'min:0', 'max:100'],
            'feedback' => ['nullable', 'string'],
        ]);

        $report->marks = $data['marks'];
        $report->feedback = $data['feedback'] ?? null;
        $report->graded_at = now();
        $report->save();

        return redirect()->route('supervisor.reports.index')->with('success', 'Report graded successfully.');
    }
}
