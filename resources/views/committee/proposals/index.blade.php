<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Project Proposals</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl font-semibold">Submitted Project Proposals</h1>
          <a href="{{ route('committee.dashboard') }}" class="text-sm text-gray-700">Back</a>
        </div>

        @if(session('success'))
          <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @if(isset($reports) && $reports->count())
          <ul class="space-y-2">
            @foreach($reports as $report)
              <li class="border rounded p-3 flex items-start justify-between">
                <div>
                  <div class="font-semibold">{{ $report->title }}</div>
                  <div class="text-sm text-gray-600">Project: {{ $report->project->title ?? 'N/A' }}</div>
                  <div class="text-sm text-gray-600">Student: {{ $report->project->user->name ?? 'N/A' }}</div>
                  <div class="text-xs text-gray-400">Submitted: {{ $report->submitted_at ? $report->submitted_at->toDayDateTimeString() : 'N/A' }}</div>
                </div>
                <div class="flex items-center space-x-2">
                  <a href="#" class="bg-blue-600 text-white px-3 py-1 rounded">View</a>
                </div>
              </li>
            @endforeach
          </ul>
        @else
          <div class="p-4 border rounded">No submitted reports found.</div>
        @endif
      </div>
    </div>
  </body>
</html>
