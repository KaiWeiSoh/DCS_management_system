<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Supervisor Dashboard</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow-lg overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <h1 class="text-2xl font-semibold">Supervisor Dashboard</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('profile.show') }}" class="text-sm text-gray-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Log out</button>
            </form>
          </div>
        </div>

        <div class="flex">
          <!-- Sidebar -->
          <aside class="w-64 bg-gray-50 border-r">
            <nav class="p-6 space-y-2">
              <h2 class="text-gray-500 uppercase tracking-wide text-xs mb-2">Sections</h2>
              <a href="{{ route('supervisor.students.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Student</a>
              <a href="{{ route('supervisor.feedback.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Feedback</a>
              <a href="{{ route('supervisor.grades.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Grade</a>
              <a href="{{ route('supervisor.meetings.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Meeting</a>
              <a href="{{ route('supervisor.milestones.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Milestone</a>
              <a href="{{ route('supervisor.project-review.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Project Review</a>
            </nav>
          </aside>

          <!-- Main content -->
          <main class="flex-1 p-6 space-y-6">
            <!-- Notifications -->
            <section class="bg-white rounded shadow p-4">
              <h3 class="text-lg font-medium">Notifications</h3>
              <div class="mt-3 space-y-2">
                @if(isset($notifications) && $notifications->count())
                  @foreach($notifications as $note)
                    <div class="border rounded p-3 bg-gray-50">
                      <div class="text-sm">{{ $note->message }}</div>
                      <div class="text-xs text-gray-400 mt-1">{{ $note->created_at->diffForHumans() }}</div>
                    </div>
                  @endforeach
                @else
                  <div class="border rounded p-3 bg-gray-50">No new notifications.</div>
                @endif
              </div>
            </section>

            <!-- Supervised Students -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Supervised Students</h3>
                <a href="{{ route('supervisor.students.index') }}" class="text-sm text-blue-600">Manage</a>
              </div>
              <ul class="mt-3 space-y-2">
                @if(isset($students) && $students->count())
                  @foreach($students as $proj)
                    <li class="border rounded p-3 flex justify-between items-center">
                      <div>
                        <div class="font-semibold">{{ $proj->user->name ?? 'Student' }}</div>
                        <div class="text-sm text-gray-600">Project: {{ $proj->title }}</div>
                      </div>
                      <a href="{{ route('profile.user', $proj->user->id) }}" class="text-sm text-blue-600">View</a>
                    </li>
                  @endforeach
                @else
                  <li class="border rounded p-3">No supervised students yet.</li>
                @endif
              </ul>
            </section>

            <!-- Feedback Provided -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Feedback Provided</h3>
                <a href="{{ route('supervisor.feedback.index') }}" class="text-sm text-blue-600">All feedback</a>
              </div>
              <div class="mt-3">
                <div class="border rounded p-3">No feedback entries yet.</div>
              </div>
            </section>

            <!-- Grades Assigned -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Grades Assigned</h3>
                <a href="{{ route('supervisor.grades.index') }}" class="text-sm text-blue-600">View grades</a>
              </div>
              <div class="mt-3">
                <div class="border rounded p-3">No grades recorded.</div>
              </div>
            </section>

            <!-- Scheduled Meetings -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Scheduled Meetings</h3>
                <a href="{{ route('supervisor.meetings.index') }}" class="text-sm text-blue-600">See schedule</a>
              </div>
              <div class="mt-3 space-y-2">
                @if(isset($meetings) && $meetings->count())
                  @foreach($meetings as $meeting)
                    <div class="border rounded p-3">
                      <div class="font-semibold">{{ $meeting->purpose }}</div>
                      <div class="text-sm text-gray-600">Student: {{ $meeting->student->name }}</div>
                      <div class="text-sm text-gray-500 mt-1">
                        {{ $meeting->meeting_date->format('d M Y') }} at {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}
                      </div>
                    </div>
                  @endforeach
                @else
                  <div class="border rounded p-3">No upcoming meetings.</div>
                @endif
              </div>
            </section>
          </main>
        </div>
      </div>
    </div>
  </body>
</html>
