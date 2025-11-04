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
            <a href="{{ route('milestones.enter') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Milestones</a>
            <a href="{{ route('fyp.index') }}" class="block px-3 py-2 rounded hover:bg-gray-100">Final Year Project</a>
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
              <h3 class="font-semibold mb-3">Notifications</h3>
              <ul class="space-y-2">
                @foreach($notifications as $n)
                  <li class="border rounded p-2">
                    <div class="text-sm">{{ $n['text'] }}</div>
                    <div class="text-xs text-gray-400">{{ \Carbon\Carbon::parse($n['time'])->diffForHumans() }}</div>
                  </li>
                @endforeach
              </ul>
            </section>

            <!-- FYP progress -->
            <section id="fyp" class="lg:col-span-1 bg-white rounded-lg shadow p-4">
              <h3 class="font-semibold mb-3">Final Year Project</h3>
              <div class="mb-3">
                <div class="text-sm text-gray-600">{{ $fyp['title'] }}</div>
                <div class="text-xs text-gray-500">Supervisor: {{ $fyp['supervisor'] }}</div>
              </div>
              <div class="w-full bg-gray-200 rounded-full h-4 mb-2">
                <div class="bg-green-500 h-4 rounded-full" style="width: {{ $fyp['progress'] }}%"></div>
              </div>
              <div class="text-sm text-gray-600">Progress: {{ $fyp['progress'] }}%</div>
            </section>

            <!-- Submitted reports -->
            <section class="lg:col-span-1 bg-white rounded-lg shadow p-4">
              <h3 class="font-semibold mb-3">Submitted Reports</h3>
              <ul class="space-y-2">
                @foreach($reports as $r)
                  <li class="flex justify-between items-center border rounded p-2">
                    <div>
                      <div class="text-sm">{{ $r['title'] }}</div>
                      <div class="text-xs text-gray-400">Submitted {{ \Carbon\Carbon::parse($r['submitted_at'])->diffForHumans() }}</div>
                    </div>
                    <a href="#" class="text-sm text-blue-600">View</a>
                  </li>
                @endforeach
              </ul>
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
