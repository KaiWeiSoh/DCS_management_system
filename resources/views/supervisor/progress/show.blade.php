<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Progress Detail - {{ $project->user->name }}</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <h1 class="text-2xl font-semibold">Progress Detail</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('supervisor.progress.index') }}" class="text-sm text-gray-700">Back</a>
            <a href="{{ route('supervisor.dashboard') }}" class="text-sm text-gray-700">Home</a>
          </div>
        </div>

        <main class="p-6 space-y-8">
          <!-- Project Summary -->
          <section class="space-y-2">
            <h2 class="text-xl font-semibold">{{ $project->title }}</h2>
            <div class="text-sm text-gray-600">Student: {{ $project->user->name }} ({{ $project->user->email }})</div>
            <div class="w-full bg-gray-200 h-4 rounded-full mt-2">
              <div class="h-4 rounded-full bg-green-500" style="width: {{ $project->progress }}%"></div>
            </div>
            <div class="text-xs text-gray-600">Overall Progress: {{ $project->progress }}%</div>
          </section>

          <!-- Reports -->
          <section>
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-lg font-medium">Submitted Reports ({{ $project->reports->count() }})</h3>
            </div>
            @if($project->reports->isEmpty())
              <div class="p-4 border rounded bg-gray-50 text-sm">No reports submitted yet.</div>
            @else
              <ul class="space-y-2">
                @foreach($project->reports as $r)
                  <li class="border rounded p-3 flex items-center justify-between bg-white">
                    <div>
                      <div class="font-medium text-sm">{{ $r->title }}</div>
                      <div class="text-xs text-gray-500">Submitted: {{ optional($r->submitted_at)->toDayDateTimeString() }}</div>
                    </div>
                    <a href="#" class="text-sm text-blue-600">View</a>
                  </li>
                @endforeach
              </ul>
            @endif
          </section>

          <!-- Milestones -->
          <section>
            <div class="flex items-center justify-between mb-3">
              <h3 class="text-lg font-medium">Milestones ({{ $project->milestones->count() }})</h3>
            </div>
            @if($project->milestones->isEmpty())
              <div class="p-4 border rounded bg-gray-50 text-sm">No milestones created yet.</div>
            @else
              <div class="overflow-x-auto">
                <table class="min-w-full text-sm">
                  <thead>
                    <tr class="text-left text-xs uppercase tracking-wide text-gray-500 border-b">
                      <th class="py-2 pr-4">Title</th>
                      <th class="py-2 pr-4">Start</th>
                      <th class="py-2 pr-4">End</th>
                      <th class="py-2 pr-4">Priority</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y">
                    @foreach($project->milestones as $m)
                      <tr>
                        <td class="py-2 pr-4">{{ $m->title }}</td>
                        <td class="py-2 pr-4">{{ optional($m->start_at)->toDayDateTimeString() ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ optional($m->due_at)->toDayDateTimeString() ?? '-' }}</td>
                        <td class="py-2 pr-4">{{ $m->priority ?? '-' }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            @endif
          </section>
        </main>
      </div>
    </div>
  </body>
</html>
