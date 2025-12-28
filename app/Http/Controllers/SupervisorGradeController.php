<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\FinalGrade;
use App\Models\StudentNotification;

class SupervisorGradeController extends Controller
{
    /**
     * Display a listing of registered FYP projects supervised by this user or where they are examiner.
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        // Get all projects supervised by this user OR where they are examiner, with their final grades
        $projects = Project::where(function($query) use ($user) {
                $query->where('supervisor_id', $user->id)
                      ->orWhere('examiner_id', $user->id);
            })
            ->with(['user', 'finalGrade'])
            ->get();

        return view('supervisor.grades.index', compact('projects'));
    }

    /**
     * Show the form for assigning final grade to a project.
     */
    public function assign($id)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['user', 'finalGrade'])
            ->where(function($query) use ($user) {
                $query->where('supervisor_id', $user->id)
                      ->orWhere('examiner_id', $user->id);
            })
            ->findOrFail($id);

        return view('supervisor.grades.assign', compact('project'));
    }

    /**
     * Store the final grade for a project.
     */
    public function storeGrade(Request $request, $id)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $project = Project::with('user')
            ->where(function($query) use ($user) {
                $query->where('supervisor_id', $user->id)
                      ->orWhere('examiner_id', $user->id);
            })
            ->findOrFail($id);

        $validated = $request->validate([
            'final_grade' => 'required|numeric|min:0|max:100',
            'comments' => 'nullable|string',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx|max:10240', // 10MB max
        ]);

        // Collect rubric data based on project type
        $rubricData = null;
        if ($project->user->project === 'FYP I') {
            // FYP I Rubric Data
            $rubricData = [
                'type' => 'FYP I',
                'prototype' => [
                    'preliminary_study' => $request->input('prototype_preliminary'),
                    'gui' => $request->input('prototype_gui'),
                    'functions' => $request->input('prototype_functions'),
                    'ownership' => $request->input('prototype_ownership'),
                    'innovation' => $request->input('prototype_innovation'),
                    'system_flow' => $request->input('prototype_system_flow'),
                ],
                'report' => [
                    'chapter1' => $request->input('report_chapter1'),
                    'chapter2' => $request->input('report_chapter2'),
                    'chapter3' => $request->input('report_chapter3'),
                    'chapter4' => $request->input('report_chapter4'),
                    'chapter5' => $request->input('report_chapter5'),
                    'structure' => $request->input('report_structure'),
                ],
                'presentation' => [
                    'preparation' => $request->input('presentation_preparation'),
                    'slides' => $request->input('presentation_slides'),
                    'content' => $request->input('presentation_content'),
                    'qa' => $request->input('presentation_qa'),
                ],
                'attitude' => [
                    'relationships' => $request->input('attitude_relationships'),
                    'planning' => $request->input('attitude_planning'),
                    'ethical' => $request->input('attitude_ethical'),
                    'independent' => $request->input('attitude_independent'),
                    'updates' => $request->input('attitude_updates'),
                ],
                'remarks' => $request->only([
                    'remarks_prototype_preliminary', 'remarks_prototype_gui', 'remarks_prototype_functions',
                    'remarks_prototype_ownership', 'remarks_prototype_innovation', 'remarks_prototype_system_flow',
                    'remarks_report_chapter1', 'remarks_report_chapter2', 'remarks_report_chapter3',
                    'remarks_report_chapter4', 'remarks_report_chapter5', 'remarks_report_structure',
                    'remarks_presentation_preparation', 'remarks_presentation_slides', 
                    'remarks_presentation_content', 'remarks_presentation_qa',
                    'remarks_attitude_relationships', 'remarks_attitude_planning', 'remarks_attitude_ethical',
                    'remarks_attitude_independent', 'remarks_attitude_updates'
                ])
            ];
        } else {
            // FYP II / Project Rubric Data
            $rubricData = [
                'type' => $project->user->project,
                'technical' => [
                    'innovation' => $request->input('technical_innovation'),
                    'functionalities' => $request->input('technical_functionalities'),
                    'quality' => $request->input('technical_quality'),
                    'effort' => $request->input('technical_effort'),
                ],
                'report' => [
                    'introduction' => $request->input('report_introduction'),
                    'analysis' => $request->input('report_analysis'),
                    'design' => $request->input('report_design'),
                    'testing' => $request->input('report_testing'),
                    'conclusion' => $request->input('report_conclusion'),
                ],
                'presentation' => [
                    'preparation' => $request->input('presentation_preparation'),
                    'slides' => $request->input('presentation_slides'),
                    'content' => $request->input('presentation_content'),
                    'qa' => $request->input('presentation_qa'),
                ],
                'remarks' => $request->only([
                    'remarks_technical_innovation', 'remarks_technical_functionalities',
                    'remarks_technical_quality', 'remarks_technical_effort',
                    'remarks_report_introduction', 'remarks_report_analysis', 'remarks_report_design',
                    'remarks_report_testing', 'remarks_report_conclusion',
                    'remarks_presentation_preparation', 'remarks_presentation_slides',
                    'remarks_presentation_content', 'remarks_presentation_qa'
                ])
            ];
        }

        $validated['rubric_data'] = $rubricData;

        // Determine if user is supervisor or examiner for this project
        $isSupervisor = ($project->supervisor_id === $user->id);
        $isExaminer = ($project->examiner_id === $user->id);

        // Handle file upload
        if ($request->hasFile('report_file')) {
            $file = $request->file('report_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('final_grade_reports', $filename, 'public');
            $validated['report_file'] = $path;
        }

        // Check if grade already exists, update or create
        $finalGrade = FinalGrade::where('project_id', $project->id)->first();
        
        if (!$finalGrade) {
            $finalGrade = new FinalGrade();
            $finalGrade->project_id = $project->id;
            $finalGrade->supervisor_id = $project->supervisor_id;
        }

        // Save grade based on role
        if ($isSupervisor) {
            $finalGrade->supervisor_grade = $validated['final_grade'];
            $finalGrade->supervisor_rubric_data = $rubricData;
            $finalGrade->supervisor_comments = $validated['comments'] ?? null;
            $finalGrade->supervisor_graded_at = now();
            $message = 'Supervisor grade assigned successfully!';
            $roleText = 'Supervisor';
        } elseif ($isExaminer) {
            $finalGrade->examiner_grade = $validated['final_grade'];
            $finalGrade->examiner_rubric_data = $rubricData;
            $finalGrade->examiner_comments = $validated['comments'] ?? null;
            $finalGrade->examiner_graded_at = now();
            $message = 'Examiner grade assigned successfully!';
            $roleText = 'Examiner';
        }

        // Calculate final grade based on project type
        if ($project->user->project === 'FYP I') {
            // FYP I only requires supervisor grade (100%)
            if ($finalGrade->supervisor_grade) {
                $finalGrade->final_grade = $finalGrade->supervisor_grade;
                $finalGrade->rubric_data = $finalGrade->supervisor_rubric_data;
                $finalGrade->comments = $finalGrade->supervisor_comments;
            }
        } else {
            // FYP II / Project requires both supervisor (60%) and examiner (40%)
            if ($finalGrade->supervisor_grade && $finalGrade->examiner_grade) {
                // Weighted average: Supervisor 60%, Examiner 40%
                $supervisorWeighted = round($finalGrade->supervisor_grade * 0.6, 2);
                $examinerWeighted = round($finalGrade->examiner_grade * 0.4, 2);
                $finalGrade->final_grade = round($supervisorWeighted + $examinerWeighted, 2);
                
                $finalGrade->rubric_data = [
                    'supervisor' => $finalGrade->supervisor_rubric_data,
                    'examiner' => $finalGrade->examiner_rubric_data,
                ];
                $finalGrade->comments = "Supervisor Comments:\n" . ($finalGrade->supervisor_comments ?? 'None') . 
                                       "\n\nExaminer Comments:\n" . ($finalGrade->examiner_comments ?? 'None');
            }
        }

        if (isset($validated['report_file'])) {
            $finalGrade->report_file = $validated['report_file'];
        }

        $finalGrade->save();

        // Send notification to the student
        $notificationMessage = "Your FYP grade has been assigned by {$roleText}: {$user->name}. Grade: {$validated['final_grade']}";
        
        if ($project->user->project === 'FYP I' && $finalGrade->supervisor_grade) {
            $notificationMessage .= "\n\nFinal Grade: {$finalGrade->final_grade}";
        } elseif ($finalGrade->supervisor_grade && $finalGrade->examiner_grade) {
            $notificationMessage .= "\n\nBoth Supervisor and Examiner have graded. Combined Final Grade: {$finalGrade->final_grade}";
        }

        // Send notification to the student
        StudentNotification::create([
            'user_id' => $project->user_id,
            'message' => $notificationMessage
        ]);

        return redirect()->route('supervisor.grades.index')
            ->with('success', $message);
    }
}
