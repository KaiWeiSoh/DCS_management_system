<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Milestones</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="max-w-4xl mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between">
          <h2 class="text-xl font-semibold">Planned Milestones</h2>
          <a href="{{ route('dashboard') }}" class="bg-gray-200 px-3 py-2 rounded">Back to Dashboard</a>
        </div>

        <div class="mt-4">
          <div class="flex items-center justify-end mb-3">
            <a href="{{ route('milestones.create') }}" class="bg-blue-600 text-white px-3 py-2 rounded">Create</a>
          </div>
          @if($milestones->isEmpty())
            <div class="text-gray-600">No milestones planned.</div>
          @else
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="text-sm text-gray-600 border-b">
                  <th class="py-2">#</th>
                  <th class="py-2">Title</th>
                  <th class="py-2">Description</th>
                  <th class="py-2">Start</th>
                  <th class="py-2">End</th>
                </tr>
              </thead>
              <tbody>
                @foreach($milestones as $index => $m)
                  <tr class="border-b">
                    <td class="py-2 align-top">{{ $index + 1 }}</td>
                    <td class="py-2 align-top">{{ $m->title }}</td>
                    <td class="py-2 align-top">{{ $m->description ?? '—' }}</td>
                    <td class="py-2 align-top">{{ $m->start_at ? \Carbon\Carbon::parse($m->start_at)->toDayDateTimeString() : ($m->created_at ? $m->created_at->toDayDateTimeString() : '—') }}</td>
                    <td class="py-2 align-top">{{ $m->due_at ? \Carbon\Carbon::parse($m->due_at)->toDayDateTimeString() : '—' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          @endif
        </div>
      </div>
    </div>
  </body>
</html>
