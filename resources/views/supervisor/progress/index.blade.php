<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Student Progress</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <h1 class="text-2xl font-semibold">Student Progress</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('supervisor.dashboard') }}" class="text-sm text-gray-700">Home</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button class="bg-red-600 text-white px-3 py-2 rounded text-sm">Log out</button>
            </form>
          </div>
        </div>

        <main class="p-6 space-y-6">
          <form method="GET" action="{{ route('supervisor.progress.index') }}" class="flex flex-col sm:flex-row sm:items-center gap-3 bg-gray-50 border rounded p-4">
            <div class="flex-1">
              <label class="block text-xs font-semibold text-gray-600 mb-1">Search Student Name</label>
              <input type="text" name="q" value="{{ $q ?? '' }}" placeholder="e.g. Alice" class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring" />
            </div>
            <div class="flex items-center gap-2 pt-1">
              <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Search</button>
              @if(!empty($q))
                <a href="{{ route('supervisor.progress.index') }}" class="text-sm text-gray-600 underline">Clear</a>
              @endif
            </div>
          </form>
          @if($projects->isEmpty())
            <div class="p-4 border rounded bg-gray-50">No approved student projects to display.</div>
          @else
            <div class="overflow-x-auto">
              <table class="min-w-full text-sm">
                <thead>
                  <tr class="text-left text-xs uppercase tracking-wide text-gray-500 border-b">
                    <th class="py-2 pr-4">Student</th>
                    <th class="py-2 pr-4">Project Title</th>
                    <th class="py-2 pr-4">Progress</th>
                    <th class="py-2 pr-4">Reports</th>
                    <th class="py-2 pr-4">Milestones</th>
                    <th class="py-2 pr-4">Action</th>
                  </tr>
                </thead>
                <tbody class="divide-y">
                  @foreach($projects as $p)
                    <tr>
                      <td class="py-2 pr-4 font-medium">{{ $p->user->name }}</td>
                      <td class="py-2 pr-4">{{ $p->title }}</td>
                      <td class="py-2 pr-4 w-48">
                        <div class="w-full bg-gray-200 h-3 rounded-full">
                          <div class="h-3 rounded-full bg-green-500" style="width: {{ $p->progress }}%"></div>
                        </div>
                        <div class="text-xs text-gray-600 mt-1">{{ $p->progress }}%</div>
                      </td>
                      <td class="py-2 pr-4">{{ $p->reports_count }}</td>
                      <td class="py-2 pr-4">{{ $p->milestones_count }}</td>
                      <td class="py-2 pr-4">
                        <a href="{{ route('supervisor.progress.show', $p->id) }}" class="text-blue-600 text-sm">View</a>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </main>
      </div>
    </div>
  </body>
</html>
