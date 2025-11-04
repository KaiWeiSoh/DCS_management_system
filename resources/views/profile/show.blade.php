<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Profile</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center space-x-6">
          @if($user->profile_picture)
            <img src="{{ asset('storage/' . $user->profile_picture) }}" alt="avatar" class="h-24 w-24 rounded-full object-cover">
          @else
            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center text-2xl text-gray-600">{{ strtoupper(substr($user->name ?? 'U',0,1)) }}</div>
          @endif

          <div>
            <h2 class="text-xl font-semibold">{{ $user->name }}</h2>
            <div class="text-sm text-gray-600">{{ $user->email }}</div>
          </div>
          <div class="ml-auto flex items-center space-x-2">
            @php
              $homeRouteName = auth()->check()
                ? (auth()->user()->role === 'supervisor' ? 'supervisor.dashboard' : (auth()->user()->role === 'committee' ? 'committee.dashboard' : 'dashboard'))
                : 'dashboard';
            @endphp
            <a href="{{ route($homeRouteName) }}" class="bg-gray-200 text-gray-800 px-3 py-2 rounded">Home</a>
            <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
          </div>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4">
          <div><strong>Gender:</strong> {{ $user->gender ?? 'Not provided' }}</div>
          <div><strong>Contact:</strong> {{ $user->contact_number ?? 'Not provided' }}</div>
          <div><strong>Subject course:</strong> {{ $user->subject_course ?? 'Not provided' }}</div>
          <div><strong>Bio:</strong>
            <div class="mt-2 text-gray-700">{{ $user->bio ?? 'No bio yet.' }}</div>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>
