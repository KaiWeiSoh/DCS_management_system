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
    Route::post('/projects/{project}/resubmit', [\App\Http\Controllers\ProjectController::class, 'resubmit'])->name('projects.resubmit');
    // Report submission
    Route::get('/reports/create', [\App\Http\Controllers\ReportController::class, 'create'])->name('reports.create');
    Route::post('/reports', [\App\Http\Controllers\ReportController::class, 'store'])->name('reports.store');
    Route::get('/reports/{report}/feedback', [\App\Http\Controllers\ReportController::class, 'viewFeedback'])->name('reports.viewFeedback');
    // Final Report submission
    Route::get('/final-report', [\App\Http\Controllers\FinalReportController::class, 'index'])->name('final-report.index');
    Route::post('/final-report', [\App\Http\Controllers\FinalReportController::class, 'store'])->name('final-report.store');
    // Task submission
    Route::get('/tasks', [\App\Http\Controllers\TaskController::class, 'index'])->name('tasks.index');
    Route::post('/tasks/{task}/submit', [\App\Http\Controllers\TaskController::class, 'submit'])->name('tasks.submit');
    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
});

// Dashboards for roles
Route::middleware('auth')->get('/supervisor/dashboard', function () {
    $user = auth()->user();
    if ($user->role !== 'supervisor') {
        return redirect()->route('dashboard');
    }
    $students = \App\Models\Project::where('supervisor_id', $user->id)->with('user')->get();
    $notifications = \App\Models\StudentNotification::where('user_id', $user->id)->orderByDesc('created_at')->take(10)->get();
    
    // Get submitted reports from supervised students
    $reports = \App\Models\Report::whereHas('project', function($query) use ($user) {
        $query->where('supervisor_id', $user->id);
    })->with(['project.user'])
      ->orderByDesc('submitted_at')
      ->take(6)
      ->get();
    
    // Get upcoming meetings
    $meetings = \App\Models\Meeting::where('supervisor_id', $user->id)
        ->where('meeting_date', '>=', now()->toDateString())
        ->with('student')
        ->orderBy('meeting_date')
        ->orderBy('meeting_time')
        ->take(5)
        ->get();
    
    return view('dashboards.supervisor', compact('students', 'notifications', 'reports', 'meetings'));
})->name('supervisor.dashboard');

Route::middleware('auth')->get('/committee/dashboard', function () {
    $user = auth()->user();
    if ($user->role !== 'committee') {
        return redirect()->route('dashboard');
    }
    
    // Get submitted FYP proposals (reports)
    $proposals = \App\Models\Report::with(['project.user'])
        ->orderByDesc('submitted_at')
        ->take(6)
        ->get();
    
    // Get upcoming deadlines
    $deadlines = \App\Models\Deadline::where('deadline_date', '>=', now()->toDateString())
        ->orderBy('deadline_date')
        ->take(5)
        ->get();
    
    return view('dashboards.committee', compact('proposals', 'deadlines'));
})->name('committee.dashboard');

// Placeholder named routes for supervisor sub-pages to avoid RouteNotFound exceptions
Route::middleware('auth')->prefix('supervisor')->name('supervisor.')->group(function () {
    Route::get('/reports', [\App\Http\Controllers\SupervisorReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}/grade', [\App\Http\Controllers\SupervisorReportController::class, 'grade'])->name('reports.grade');
    Route::post('/reports/{report}/grade', [\App\Http\Controllers\SupervisorReportController::class, 'storeGrade'])->name('reports.storeGrade');

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

    Route::get('/feedback', [\App\Http\Controllers\SupervisorFeedbackController::class, 'index'])->name('feedback.index');
    Route::post('/feedback/{submission}', [\App\Http\Controllers\SupervisorFeedbackController::class, 'store'])->name('feedback.store');

    Route::get('/grades', [\App\Http\Controllers\SupervisorGradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{project}/assign', [\App\Http\Controllers\SupervisorGradeController::class, 'assign'])->name('grades.assign');
    Route::post('/grades/{project}/assign', [\App\Http\Controllers\SupervisorGradeController::class, 'storeGrade'])->name('grades.store');

    Route::get('/meetings', [\App\Http\Controllers\SupervisorMeetingController::class, 'index'])->name('meetings.index');
    Route::get('/meetings/create', [\App\Http\Controllers\SupervisorMeetingController::class, 'create'])->name('meetings.create');
    Route::post('/meetings', [\App\Http\Controllers\SupervisorMeetingController::class, 'store'])->name('meetings.store');

    Route::get('/milestones', [\App\Http\Controllers\SupervisorMilestoneController::class, 'index'])->name('milestones.index');
    Route::get('/milestones/create', [\App\Http\Controllers\SupervisorMilestoneController::class, 'create'])->name('milestones.create');
    Route::post('/milestones', [\App\Http\Controllers\SupervisorMilestoneController::class, 'store'])->name('milestones.store');
    Route::get('/milestones/{task}', [\App\Http\Controllers\SupervisorMilestoneController::class, 'view'])->name('milestones.view');

    Route::get('/project-review', [\App\Http\Controllers\SupervisorProjectReviewController::class, 'index'])->name('project-review.index');
    Route::get('/project-review/{project}', [\App\Http\Controllers\SupervisorProjectReviewController::class, 'show'])->name('project-review.show');

    Route::get('/progress', [\App\Http\Controllers\SupervisorProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/{project}', [\App\Http\Controllers\SupervisorProgressController::class, 'show'])->name('progress.show');

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
    
    Route::get('/grades', [\App\Http\Controllers\CommitteeGradeController::class, 'index'])->name('grades.index');
    Route::get('/grades/{project}/review', [\App\Http\Controllers\CommitteeGradeController::class, 'review'])->name('grades.review');
    Route::get('/grades/{project}/rubric/supervisor', [\App\Http\Controllers\CommitteeGradeController::class, 'viewSupervisorRubric'])->name('grades.rubric.supervisor');
    Route::get('/grades/{project}/rubric/examiner', [\App\Http\Controllers\CommitteeGradeController::class, 'viewExaminerRubric'])->name('grades.rubric.examiner');
    Route::post('/grades/{project}/finalize', [\App\Http\Controllers\CommitteeGradeController::class, 'finalize'])->name('grades.finalize');
    Route::post('/grades/{project}/reject', [\App\Http\Controllers\CommitteeGradeController::class, 'reject'])->name('grades.reject');
    
    Route::get('/examiners', [\App\Http\Controllers\CommitteeExaminerController::class, 'index'])->name('examiners.index');
    Route::post('/examiners/{project}/assign', [\App\Http\Controllers\CommitteeExaminerController::class, 'assign'])->name('examiners.assign');
    
    Route::get('/deadlines', [\App\Http\Controllers\CommitteeDeadlineController::class, 'index'])->name('deadlines.index');
    Route::get('/deadlines/create', [\App\Http\Controllers\CommitteeDeadlineController::class, 'create'])->name('deadlines.create');
    Route::post('/deadlines', [\App\Http\Controllers\CommitteeDeadlineController::class, 'store'])->name('deadlines.store');
});

// Authentication routes (minimal)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
