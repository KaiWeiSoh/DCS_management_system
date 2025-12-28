<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Dashboard</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="max-w-7xl mx-auto p-6">
      <div class="flex">
        <!-- Sidebar -->
            <aside class="w-64 bg-white rounded-lg shadow p-4 mr-6">
          <h2 class="font-bold text-lg mb-4">Menu</h2>
          <nav class="space-y-2">
            <a href="{{ route('fyp.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Final Year Project</a>
            <a href="{{ route('final-report.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Final Report</a>
            <a href="{{ route('tasks.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Task Submission</a>
            <a href="{{ route('chat') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Chat</a>
          </nav>
        </aside>

        <!-- Main content -->
        <main class="flex-1">
          <div class="bg-white rounded-lg shadow p-6 mb-6 flex items-center justify-between">
            <div>
              <h1 class="text-2xl font-semibold">Student Dashboard</h1>
              <p class="text-sm text-gray-500">Overview of notifications, project progress, submitted reports and upcoming deadlines.</p>
            </div>
            <div class="flex items-center space-x-3">
              @php $user = auth()->user(); @endphp
              <a href="{{ route('profile.show') }}" title="Your profile">
                @if($user && $user->profile_picture)
                  <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="avatar" class="h-10 w-10 rounded-full object-cover border">
                @else
                  <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600">{{ strtoupper(substr($user->name ?? 'U',0,1)) }}</div>
                @endif
              </a>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Log out</button>
              </form>
            </div>
          </div>

          <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Notifications -->
            <section class="lg:col-span-1 bg-white rounded-lg shadow p-4">
                <div class="flex justify-between items-center mb-3">
                  <h3 class="font-semibold">Notifications</h3>
                  @if($totalNotifications > 2)
                    <a href="{{ route('notifications.index') }}" class="text-xs text-blue-600 hover:text-blue-800">More</a>
                  @endif
                </div>
              <ul class="space-y-2">
                @forelse($notifications as $notification)
                  <li class="border rounded p-2 {{ $notification->read_at ? 'bg-gray-50' : 'bg-blue-50' }}">
                    <div class="text-sm">{{ $notification->message }}</div>
                    <div class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                  </li>
                @empty
                  <li class="border rounded p-2">
                    <div class="text-sm text-gray-500">No notifications yet.</div>
                  </li>
                @endforelse
              </ul>
            </section>

            <!-- FYP Title -->
            <section id="fyp" class="lg:col-span-1 bg-white rounded-lg shadow p-4">
              <h3 class="font-semibold mb-3">Final Year Project</h3>
              <div class="mb-3">
                <div class="text-sm font-medium text-gray-800">{{ $fyp['title'] }}</div>
                <div class="text-xs text-gray-500 mt-2">Supervisor: {{ $fyp['supervisor'] }}</div>
              </div>
            </section>

            <!-- Task Submission -->
            <section class="lg:col-span-1 bg-white rounded-lg shadow p-4">
              <h3 class="font-semibold mb-3">Task Submission</h3>
              @if($taskStats['total'] > 0)
                <div class="space-y-3">
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Total Tasks:</span>
                    <span class="text-lg font-semibold text-gray-800">{{ $taskStats['total'] }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Submitted:</span>
                    <span class="text-lg font-semibold text-green-600">{{ $taskStats['submitted'] }}</span>
                  </div>
                  <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600">Pending:</span>
                    <span class="text-lg font-semibold text-yellow-600">{{ $taskStats['pending'] }}</span>
                  </div>
                  @if($taskStats['with_feedback'] > 0)
                    <div class="flex justify-between items-center">
                      <span class="text-sm text-gray-600">With Feedback:</span>
                      <span class="text-lg font-semibold text-blue-600">{{ $taskStats['with_feedback'] }}</span>
                    </div>
                  @endif
                </div>
              @else
                <div class="text-sm text-gray-500">No tasks assigned yet.</div>
              @endif
            </section>
          </div>

          <div id="milestones" class="mt-6 bg-white rounded-lg shadow p-4">
            <h3 class="font-semibold mb-3">Upcoming Milestones</h3>
            <table class="w-full text-left">
              <thead>
                <tr class="text-xs text-gray-500">
                  <th class="py-2">Milestone</th>
                  <th class="py-2">Due</th>
                  <th class="py-2">Time left</th>
                </tr>
              </thead>
              <tbody class="divide-y">
                @foreach($milestones as $m)
                  <tr>
                    <td class="py-2">{{ $m['title'] }}</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($m['due_at'])->toDayDateTimeString() }}</td>
                    <td class="py-2 text-sm text-gray-600">{{ \Carbon\Carbon::parse($m['due_at'])->diffForHumans(null, true) }} left</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </main>
      </div>
    </div>
  </body>
</html>
