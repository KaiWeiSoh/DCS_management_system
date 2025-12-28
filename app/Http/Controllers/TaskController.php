<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Task;
use App\Models\StudentNotification;

class TaskController extends Controller
{
    /**
     * Display the task submission page for students.
     */
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }

        // Get all tasks assigned to this student
        $tasks = Task::where('student_id', $user->id)
            ->with('supervisor')
            ->orderByDesc('created_at')
            ->get();

        return view('tasks.index', compact('tasks'));
    }

    /**
     * Submit a file for a task.
     */
    public function submit(Request $request, $id)
    {
        $user = Auth::user();
        
        if ($user->role !== 'student') {
            return redirect()->route('dashboard');
        }

        $task = Task::where('id', $id)
            ->where('student_id', $user->id)
            ->firstOrFail();

        $validated = $request->validate([
            'submission_file' => 'required|file|mimes:pdf,doc,docx,zip|max:10240', // 10MB max
        ]);

        // Handle file upload
        if ($request->hasFile('submission_file')) {
            $file = $request->file('submission_file');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('task_submissions', $filename, 'public');
            
            $task->update([
                'submission_file' => $path,
                'submitted_at' => now(),
                'status' => 'completed'
            ]);

            // Send notification to supervisor
            StudentNotification::create([
                'user_id' => $task->supervisor_id,
                'message' => "{$user->name} has submitted the task: {$task->title}"
            ]);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task submitted successfully!');
    }
}
