<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Project Approval</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl font-semibold">Project Approval</h1>
          <a href="{{ route('committee.dashboard') }}" class="text-sm text-gray-700">Back</a>
        </div>

        @if(session('success'))
          <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @if($projects->isEmpty())
          <div class="p-4 border rounded">No registered project titles.</div>
        @else
          <ul class="space-y-2">
            @foreach($projects as $p)
              <li class="border rounded p-3">
                <div class="flex items-start justify-between">
                  <div>
                    <div class="font-semibold">{{ $p->title }}</div>
                    <div class="text-sm text-gray-600">Student: {{ $p->user->name }} ({{ $p->user->email }})</div>
                    <div class="text-xs text-gray-400">Registered: {{ $p->created_at->toDayDateTimeString() }}</div>
                    @if($p->supervisorUser)
                      <div class="text-xs text-gray-500">Supervisor: {{ $p->supervisorUser->name }}</div>
                    @endif
                  </div>

                  <div class="text-right">
                    @if(is_null($p->approved))
                      <div class="mb-2"><span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800 text-sm">Pending</span></div>
                      <div class="flex items-center justify-end space-x-2">
                        <form method="POST" action="{{ route('committee.proposals.approve', $p->id) }}">
                          @csrf
                          <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded">Approve</button>
                        </form>
                        <form method="POST" action="{{ route('committee.proposals.reject', $p->id) }}">
                          @csrf
                          <button type="submit" class="bg-red-600 text-white px-3 py-1 rounded">Reject</button>
                        </form>
                      </div>
                    @elseif($p->approved === true)
                      <div class="mb-2"><span class="px-2 py-1 rounded bg-green-100 text-green-800 text-sm">Approved</span></div>
                      <div class="text-xs text-gray-500">Approved at: {{ optional($p->approved_at)->toDayDateTimeString() }}</div>
                    @else
                      <div class="mb-2"><span class="px-2 py-1 rounded bg-red-100 text-red-800 text-sm">Rejected</span></div>
                      <div class="text-xs text-gray-500">Rejected at: {{ optional($p->approved_at)->toDayDateTimeString() }}</div>
                    @endif
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
        @endif
      </div>
    </div>
  </body>
</html>
