<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    // Redirect authenticated users to dashboard, guests see the login page directly
    return auth()->check()
        ? redirect()->route('dashboard')
        : view('auth.login');
});

// Generic chat routes for students (and general use)
Route::middleware('auth')->group(function () {
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.send');
    Route::get('/chat/messages', [\App\Http\Controllers\ChatController::class, 'messages'])->name('chat.messages');
});

// Student dashboard (requires authentication)
Route::get('/dashboard', [StudentDashboardController::class, 'index'])->middleware('auth')->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Allow supervisors/committee to view other users' profiles by id (role-specific view)
Route::middleware('auth')->get('/users/{id}/profile', [ProfileController::class, 'show'])->name('profile.user');

// Milestones: enter (from dashboard) and index
Route::middleware('auth')->group(function () {
    Route::get('/milestones/enter', [\App\Http\Controllers\MilestoneController::class, 'enter'])->name('milestones.enter');
    Route::get('/milestones', [\App\Http\Controllers\MilestoneController::class, 'index'])->name('milestones.index');
    Route::get('/milestones/create', [\App\Http\Controllers\MilestoneController::class, 'create'])->name('milestones.create');
    Route::post('/milestones', [\App\Http\Controllers\MilestoneController::class, 'store'])->name('milestones.store');
});

// Student FYP and project routes
Route::middleware('auth')->group(function () {
    Route::get('/fyp', [\App\Http\Controllers\ProjectController::class, 'index'])->name('fyp.index');
    Route::get('/projects/create', [\App\Http\Controllers\ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [\App\Http\Controllers\ProjectController::class, 'store'])->name('projects.store');
});

// Dashboards for roles
Route::middleware('auth')->get('/supervisor/dashboard', function () {
    $user = auth()->user();
    if ($user->role !== 'supervisor') {
        return redirect()->route('dashboard');
    }
    $students = \App\Models\Project::where('supervisor_id', $user->id)->with('user')->get();
    $notifications = \App\Models\StudentNotification::where('user_id', $user->id)->orderByDesc('created_at')->take(10)->get();
    return view('dashboards.supervisor', compact('students', 'notifications'));
})->name('supervisor.dashboard');

Route::middleware('auth')->get('/committee/dashboard', function () {
    $user = auth()->user();
    if ($user->role !== 'committee') {
        return redirect()->route('dashboard');
    }
    return view('dashboards.committee');
})->name('committee.dashboard');

// Placeholder named routes for supervisor sub-pages to avoid RouteNotFound exceptions
Route::middleware('auth')->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/reports', function () {
        $user = auth()->user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        // Placeholder: redirect to supervisor dashboard for now
        return redirect()->route('supervisor.dashboard');
    })->name('reports.index');

    Route::get('/students', function () {
        $user = auth()->user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        // Fetch projects assigned to this supervisor, then filter approved ones in PHP
        $projects = \App\Models\Project::where('supervisor_id', $user->id)
            ->with('user')
            ->orderByDesc('created_at')
            ->get();

        // Filter approved projects robustly (handles boolean/int/string DB differences)
        $students = $projects->filter(function ($p) {
            return !is_null($p->approved) && ($p->approved === true || $p->approved == 1 || $p->approved === '1' || $p->approved === 'true');
        })->values();

        return view('supervisor.students.index', compact('students'));
    })->name('students.index');

    Route::get('/feedback', function () {
        $user = auth()->user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('supervisor.dashboard');
    })->name('feedback.index');

    Route::get('/grades', function () {
        $user = auth()->user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('supervisor.dashboard');
    })->name('grades.index');

    Route::get('/meetings', function () {
        $user = auth()->user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('supervisor.dashboard');
    })->name('meetings.index');

    Route::get('/progress', function () {
        $user = auth()->user();
        if ($user->role !== 'supervisor') {
            return redirect()->route('dashboard');
        }
        return redirect()->route('supervisor.dashboard');
    })->name('progress.index');

    // Chat routes (supervisor <-> student)
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat');
    Route::post('/chat/send', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.send');
    Route::get('/chat/messages', [\App\Http\Controllers\ChatController::class, 'messages'])->name('chat.messages');
});

// Committee routes: proposals and approval actions
Route::middleware('auth')->prefix('committee')->name('committee.')->group(function () {
    Route::get('/proposals', [\App\Http\Controllers\CommitteeController::class, 'proposals'])->name('proposals.index');
    Route::post('/proposals/{id}/approve', [\App\Http\Controllers\CommitteeController::class, 'approve'])->name('proposals.approve');
    Route::post('/proposals/{id}/reject', [\App\Http\Controllers\CommitteeController::class, 'reject'])->name('proposals.reject');

    // Project approval page shows registered project titles pending approval
    Route::get('/approvals', [\App\Http\Controllers\CommitteeController::class, 'approvals'])->name('approvals.index');
    Route::get('/grades', function () { return redirect()->route('committee.dashboard'); })->name('grades.index');
    Route::get('/deadlines', function () { return redirect()->route('committee.dashboard'); })->name('deadlines.index');
});

// Authentication routes (minimal)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
