<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;

class SupervisorProgressController extends Controller
{
    // List progress for all supervised students
    public function index(Request $request)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        $q = trim($request->query('q', ''));

        $query = Project::where('supervisor_id', $user->id)
            ->where('approved', 1)
            ->withCount(['reports', 'milestones'])
            ->with('user');

        if ($q !== '') {
            $query->whereHas('user', function ($sub) use ($q) {
                $sub->where('name', 'like', "%$q%");
            });
        }

        $projects = $query->orderBy('created_at')->get();

        return view('supervisor.progress.index', [
            'projects' => $projects,
            'q' => $q,
        ]);
    }

    // Show detailed progress for a single student project
    public function show($id)
    {
        $user = Auth::user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }

        $project = Project::with(['reports' => function ($q) {
                $q->orderByDesc('submitted_at');
            }, 'milestones' => function ($q) {
                $q->orderBy('due_at');
            }, 'user'])
            ->where('supervisor_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();

        return view('supervisor.progress.show', compact('project'));
    }
}
