<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Show a profile. If no id is provided, show the authenticated user's profile.
     * If an id is provided, load that user and render a role-specific view.
     */
    public function show($id = null)
    {
        if ($id) {
            $user = User::findOrFail($id);
        } else {
            $user = Auth::user();
        }

        // Choose a role-specific view if available
        switch ($user->role ?? 'student') {
            case 'supervisor':
                return view('profile.supervisor_show', compact('user'));
            case 'committee':
                return view('profile.committee_show', compact('user'));
            case 'student':
            default:
                return view('profile.student_show', compact('user'));
        }
    }

    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'gender' => ['nullable','in:male,female,other'],
            'contact_number' => ['nullable','string','max:50'],
            'bio' => ['nullable','string','max:2000'],
            'project' => ['nullable','in:Project,FYP I,FYP II'],
            'programme' => ['nullable','in:DIT,DCS,BOS'],
            'profile_picture' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $path = $file->store('profile_pictures', 'public');

            // delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }

            $data['profile_picture'] = $path;
        }

        $user->update($data);

        return redirect()->route('profile.show')->with('success','Profile updated.');
    }
}
