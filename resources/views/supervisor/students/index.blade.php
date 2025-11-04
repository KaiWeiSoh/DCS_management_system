<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Supervised Students</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow-lg overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <h1 class="text-2xl font-semibold">Supervised Students</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('supervisor.dashboard') }}" class="text-sm text-gray-700">Back</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Log out</button>
            </form>
          </div>
        </div>

        <main class="p-6">
          @if(isset($students) && $students->count())
            <div class="grid gap-4">
              @foreach($students as $proj)
                <div class="border rounded p-4 flex items-center justify-between">
                  <div>
                    <div class="font-semibold">{{ $proj->user->name ?? 'Student' }}</div>
                    <div class="text-sm text-gray-600">Course: {{ $proj->user->subject_course ?? 'N/A' }}</div>
                    <div class="text-sm text-gray-600">Project: {{ $proj->title }}</div>
                  </div>
                  <div class="space-x-2">
                    <a href="{{ route('profile.user', $proj->user->id) }}" class="text-sm text-blue-600">View Profile</a>
                    <a href="{{ route('supervisor.chat', ['student_id' => $proj->user->id]) }}" class="bg-blue-600 text-white px-3 py-2 rounded">Chat</a>
                  </div>
                </div>
              @endforeach
            </div>
          @else
            <div class="border rounded p-4">No approved students assigned to you yet.</div>
          @endif
        </main>
      </div>
    </div>
  </body>
</html>
