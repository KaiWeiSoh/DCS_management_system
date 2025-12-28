<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\ProjectSubmission;
use App\Models\StudentNotification;

class FinalReportController extends Controller
{
    /**
     * Display the final report submission page for students.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get the student's project and submission
        $project = Project::where('user_id', $user->id)->first();
        $submission = $project ? ProjectSubmission::where('project_id', $project->id)->first() : null;
        
        return view('final-report.index', compact('project', 'submission', 'user'));
    }

    /**
     * Store a newly submitted project submission.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Get user's project
        $project = Project::where('user_id', $user->id)->first();
        
        if (!$project) {
            return back()->with('error', 'No project found. Please register a project first.');
        }

        // Validation rules based on project type
        if ($user->project === 'FYP I') {
            // FYP I students only need to submit report
            $rules = [
                'report' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'], // 20MB max
            ];
        } else {
            // FYP II and Project students follow programme-based requirements
            $rules = [
                'project_title' => ['required', 'string', 'max:255'],
                'abstract' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:5120'], // 5MB max
                'extended_abstract' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:10240'], // 10MB max
                'source_code' => ['required', 'file', 'mimes:zip,rar', 'max:51200'], // 50MB max
                'report' => ['required', 'file', 'mimes:pdf,doc,docx', 'max:20480'], // 20MB max
            ];

            // Add video requirement for DIT and BOS programmes
            if (in_array($user->programme, ['DIT', 'BOS'])) {
                $rules['presentation_video'] = ['required', 'file', 'mimes:mp4,avi,mov,wmv', 'max:102400']; // 100MB max
            }
        }

        $request->validate($rules);

        // Get existing submission or create new
        $submission = ProjectSubmission::firstOrNew(['project_id' => $project->id]);

        // Handle abstract upload
        if ($request->hasFile('abstract')) {
            // Delete old file if exists
            if ($submission->abstract && \Storage::disk('public')->exists($submission->abstract)) {
                \Storage::disk('public')->delete($submission->abstract);
            }
            
            $file = $request->file('abstract');
            $filename = time() . '_abstract_' . $file->getClientOriginalName();
            $submission->abstract = $file->storeAs('project_submissions/abstracts', $filename, 'public');
        }

        // Handle extended abstract upload
        if ($request->hasFile('extended_abstract')) {
            // Delete old file if exists
            if ($submission->extended_abstract && \Storage::disk('public')->exists($submission->extended_abstract)) {
                \Storage::disk('public')->delete($submission->extended_abstract);
            }
            
            $file = $request->file('extended_abstract');
            $filename = time() . '_extended_abstract_' . $file->getClientOriginalName();
            $submission->extended_abstract = $file->storeAs('project_submissions/extended_abstracts', $filename, 'public');
        }

        // Handle source code upload
        if ($request->hasFile('source_code')) {
            // Delete old file if exists
            if ($submission->source_code && \Storage::disk('public')->exists($submission->source_code)) {
                \Storage::disk('public')->delete($submission->source_code);
            }
            
            $file = $request->file('source_code');
            $filename = time() . '_source_code_' . $file->getClientOriginalName();
            $submission->source_code = $file->storeAs('project_submissions/source_code', $filename, 'public');
        }

        // Handle report upload
        if ($request->hasFile('report')) {
            // Delete old file if exists
            if ($submission->report && \Storage::disk('public')->exists($submission->report)) {
                \Storage::disk('public')->delete($submission->report);
            }
            
            $file = $request->file('report');
            $filename = time() . '_report_' . $file->getClientOriginalName();
            $submission->report = $file->storeAs('project_submissions/reports', $filename, 'public');
        }

        // Handle video upload for DIT and BOS
        if ($request->hasFile('presentation_video')) {
            // Delete old file if exists
            if ($submission->presentation_video && \Storage::disk('public')->exists($submission->presentation_video)) {
                \Storage::disk('public')->delete($submission->presentation_video);
            }
            
            $file = $request->file('presentation_video');
            $filename = time() . '_video_' . $file->getClientOriginalName();
            $submission->presentation_video = $file->storeAs('project_submissions/videos', $filename, 'public');
        }

        // Update project title and submission timestamp
        if ($user->project !== 'FYP I' && $request->has('project_title')) {
            $project->title = $request->project_title;
            $project->save();
        }
        
        $submission->project_id = $project->id;
        $submission->submitted_at = now();
        $submission->save();

        // Notify supervisor if student has one
        if ($project->supervisor_id) {
            StudentNotification::create([
                'user_id' => $project->supervisor_id,
                'message' => $user->name . ' has submitted their project materials.',
                'type' => 'project_submission',
            ]);
        }

        return redirect()->route('final-report.index')->with('success', 'Project submission successful!');
    }
}
