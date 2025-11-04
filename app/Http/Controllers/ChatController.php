<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Message;

class ChatController extends Controller
{
    // show chat UI between auth user (supervisor) and a student
    public function index(Request $request)
    {
        $user = Auth::user();

        // allow supervisor or student
        if (!in_array($user->role, ['supervisor', 'student'])) {
            return redirect()->route('dashboard');
        }

        $other = null;
        $messages = collect();

        if ($user->role === 'supervisor') {
            $studentId = $request->query('student_id');
            if ($studentId) {
                $other = User::findOrFail($studentId);
            }
        } else { // student
            // find this student's project and assigned supervisor
            $project = $user->projects()->first();
            if ($project && $project->supervisor_id) {
                $other = User::find($project->supervisor_id);
            }
        }

        if ($other) {
            $messages = Message::where(function ($q) use ($user, $other) {
                $q->where('from_user_id', $user->id)->where('to_user_id', $other->id);
            })->orWhere(function ($q) use ($user, $other) {
                $q->where('from_user_id', $other->id)->where('to_user_id', $user->id);
            })->orderBy('created_at')->get();
        }

        return view('chat.index', ['other' => $other, 'messages' => $messages]);
    }

    // store a new message (supervisor -> student)
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['supervisor', 'student'])) {
            return redirect()->route('dashboard');
        }

        $data = $request->validate([
            'to_user_id' => ['required', 'integer'],
            'message' => ['required', 'string'],
        ]);

        $to = User::findOrFail($data['to_user_id']);

        Message::create([
            'from_user_id' => $user->id,
            'to_user_id' => $to->id,
            'message' => $data['message'],
        ]);

        // redirect back to the proper chat page
        if ($user->role === 'supervisor') {
            return redirect()->route('supervisor.chat', ['student_id' => $to->id]);
        }
        return redirect()->route('chat');
    }

    // return JSON messages between auth user and the given student (for polling)
    public function messages(Request $request)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['supervisor', 'student'])) {
            return response()->json([], 403);
        }
        $otherId = $request->query('other_id') ?? $request->query('student_id');
        if (!$otherId) {
            return response()->json([]);
        }

        $messages = Message::where(function ($q) use ($user, $otherId) {
            $q->where('from_user_id', $user->id)->where('to_user_id', $otherId);
        })->orWhere(function ($q) use ($user, $otherId) {
            $q->where('from_user_id', $otherId)->where('to_user_id', $user->id);
        })->orderBy('created_at')->get();

        return response()->json($messages);
    }
}
