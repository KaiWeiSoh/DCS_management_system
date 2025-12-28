<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\FinalGrade;
use App\Models\StudentNotification;

class CommitteeGradeController extends Controller
{
    /**
     * Display a listing of all FYP projects with their grades.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        // Get all projects with final grades, ordered by status (pending first) and updated date
        $projects = Project::whereHas('finalGrade')
            ->with(['user', 'finalGrade.supervisor', 'supervisorUser', 'examinerUser'])
            ->get()
            ->sortBy(function($project) {
                // Sort: pending grades first, then by most recently updated
                return [
                    $project->finalGrade->finalized_at ? 1 : 0,
                    -$project->finalGrade->updated_at->timestamp
                ];
            });

        return view('committee.grades.index', compact('projects'));
    }

    /**
     * Show the review page for a specific project grade.
     */
    public function review($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['user', 'finalGrade.supervisor', 'supervisorUser', 'examinerUser'])
            ->whereHas('finalGrade')
            ->findOrFail($id);

        $finalGrade = $project->finalGrade;

        return view('committee.grades.review', compact('project', 'finalGrade'));
    }

    /**
     * View supervisor's detailed rubric assessment.
     */
    public function viewSupervisorRubric($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['user', 'finalGrade', 'supervisorUser'])
            ->whereHas('finalGrade')
            ->findOrFail($id);

        $finalGrade = $project->finalGrade;

        return view('committee.grades.rubric-view', [
            'project' => $project,
            'finalGrade' => $finalGrade,
            'rubricData' => $finalGrade->supervisor_rubric_data,
            'grade' => $finalGrade->supervisor_grade,
            'comments' => $finalGrade->supervisor_comments,
            'gradedAt' => $finalGrade->supervisor_graded_at,
            'graderName' => $project->supervisorUser->name ?? 'N/A',
            'role' => 'Supervisor',
            'roleColor' => 'green'
        ]);
    }

    /**
     * View examiner's detailed rubric assessment.
     */
    public function viewExaminerRubric($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['user', 'finalGrade', 'examinerUser'])
            ->whereHas('finalGrade')
            ->findOrFail($id);

        $finalGrade = $project->finalGrade;

        return view('committee.grades.rubric-view', [
            'project' => $project,
            'finalGrade' => $finalGrade,
            'rubricData' => $finalGrade->examiner_rubric_data,
            'grade' => $finalGrade->examiner_grade,
            'comments' => $finalGrade->examiner_comments,
            'gradedAt' => $finalGrade->examiner_graded_at,
            'graderName' => $project->examinerUser->name ?? 'N/A',
            'role' => 'Examiner',
            'roleColor' => 'purple'
        ]);
    }

    /**
     * Finalize the grade for a specific project.
     */
    public function finalize($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['user', 'finalGrade'])
            ->whereHas('finalGrade')
            ->findOrFail($id);

        $finalGrade = $project->finalGrade;

        if ($finalGrade->finalized_at) {
            return redirect()->route('committee.grades.index')
                ->with('error', 'This grade has already been finalized.');
        }

        // Check if both supervisor and examiner have graded
        if (!$finalGrade->supervisor_grade || !$finalGrade->examiner_grade) {
            return redirect()->route('committee.grades.review', $id)
                ->with('error', 'Cannot finalize: Both Supervisor and Examiner must submit their grades first.');
        }

        // Finalize the grade
        $finalGrade->update([
            'finalized_at' => now(),
            'finalized_by' => $user->id,
            'status' => 'approved'
        ]);

        // Send notification to student
        StudentNotification::create([
            'user_id' => $project->user_id,
            'message' => "Your FYP final grade has been finalized by the committee. Grade: {$finalGrade->final_grade}"
        ]);

        // Send notification to supervisor
        StudentNotification::create([
            'user_id' => $project->supervisor_id,
            'message' => "The final grade for project '{$project->title}' has been finalized by the committee."
        ]);

        // Send notification to examiner if assigned
        if ($project->examiner_id) {
            StudentNotification::create([
                'user_id' => $project->examiner_id,
                'message' => "The final grade for project '{$project->title}' has been finalized by the committee."
            ]);
        }

        return redirect()->route('committee.grades.review', $project->id)
            ->with('success', 'Grade has been successfully finalized.');
    }

    /**
     * Reject the grade for a specific project.
     */
    public function reject($id)
    {
        $user = Auth::user();
        if ($user->role !== 'committee') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['user', 'finalGrade'])
            ->whereHas('finalGrade')
            ->findOrFail($id);

        $finalGrade = $project->finalGrade;

        if ($finalGrade->finalized_at) {
            return redirect()->route('committee.grades.index')
                ->with('error', 'This grade has already been finalized and cannot be rejected.');
        }

        // Update status to rejected
        $finalGrade->update([
            'status' => 'rejected'
        ]);

        // Send notification to supervisor to review and assign new grade
        StudentNotification::create([
            'user_id' => $project->supervisor_id,
            'message' => "The committee has rejected the final grade for project '{$project->title}'. Please review and assign a new grade."
        ]);

        // Send notification to examiner if assigned
        if ($project->examiner_id) {
            StudentNotification::create([
                'user_id' => $project->examiner_id,
                'message' => "The committee has rejected the final grade for project '{$project->title}'. Please review and assign a new grade."
            ]);
        }

        // Send notification to student
        StudentNotification::create([
            'user_id' => $project->user_id,
            'message' => "The committee is reviewing your FYP final grade. Your supervisor will be assigning a revised grade."
        ]);

        return redirect()->route('committee.grades.review', $project->id)
            ->with('success', 'Grade has been rejected. Supervisor and examiner have been notified to review and assign a new grade.');
    }
}
