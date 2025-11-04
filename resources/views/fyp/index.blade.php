<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Final Year Project</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl font-semibold">Final Year Project</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-700">Back</a>
          </div>
        </div>

        @if(session('success'))
          <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @if(!$project)
          <div class="p-4 border rounded bg-gray-50">
            <p>You don't have a registered project yet. Please register a project title and choose a supervisor.</p>
            <a href="{{ route('projects.create') }}" class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded">Register Project</a>
          </div>
        @else
          <div class="mb-4">
            <div class="text-lg font-semibold">Project: {{ $project->title }}</div>
            <div class="text-sm text-gray-600">Supervisor: {{ $project->supervisor ?? 'Not assigned' }}</div>
            <div class="text-sm mt-2">
              <strong>Approval status:</strong>
              @if(is_null($project->approved))
                <span class="text-yellow-600">Pending</span>
              @elseif($project->approved)
                <span class="text-green-600">Approved</span>
              @else
                <span class="text-red-600">Refused</span>
              @endif
            </div>
          </div>

          <h3 class="font-semibold mb-3">Submitted Reports</h3>
          <ul class="space-y-2">
            @forelse($project->reports as $report)
              <li class="flex justify-between items-center border rounded p-2">
                <div>
                  <div class="text-sm">{{ $report->title }}</div>
                  <div class="text-xs text-gray-400">Submitted {{ $report->submitted_at ? $report->submitted_at->diffForHumans() : 'N/A' }}</div>
                </div>
                <div class="text-sm text-gray-600">Status: Pending</div>
              </li>
            @empty
              <li class="border rounded p-3">No reports submitted yet.</li>
            @endforelse
          </ul>
        @endif
      </div>
    </div>
  </body>
</html>
