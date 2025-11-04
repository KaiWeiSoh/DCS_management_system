<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Project;
use App\Models\Report;
use App\Models\Milestone;
use App\Models\StudentNotification;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class StudentDashboardSeeder extends Seeder
{
    public function run()
    {
        // Create a test user
        $user = User::firstOrCreate([
            'email' => 'student@example.com'
        ], [
            'name' => 'Student One',
            'password' => Hash::make('password'),
        ]);

        // Create a project
        $project = Project::firstOrCreate([
            'user_id' => $user->id,
        ], [
            'title' => 'Smart Campus Attendance',
            'progress' => 62,
            'supervisor' => 'Dr. Lim',
        ]);

        // Reports
        Report::firstOrCreate([
            'project_id' => $project->id,
            'title' => 'Proposal',
        ], [
            'submitted_at' => Carbon::now()->subWeeks(6),
        ]);

        Report::firstOrCreate([
            'project_id' => $project->id,
            'title' => 'Progress Report 1',
        ], [
            'submitted_at' => Carbon::now()->subWeeks(2),
        ]);

        // Milestones
        Milestone::firstOrCreate([
            'project_id' => $project->id,
            'title' => 'Proposal Submission',
        ], [
            'due_at' => Carbon::now()->addDays(5),
        ]);

        Milestone::firstOrCreate([
            'project_id' => $project->id,
            'title' => 'Mid-term Demo',
        ], [
            'due_at' => Carbon::now()->addWeeks(2)->addDays(1),
        ]);

        // Notifications
        StudentNotification::firstOrCreate([
            'user_id' => $user->id,
            'message' => 'Supervisor approved proposal',
        ]);

        StudentNotification::firstOrCreate([
            'user_id' => $user->id,
            'message' => 'New comment on report 1',
        ]);
    }
}
