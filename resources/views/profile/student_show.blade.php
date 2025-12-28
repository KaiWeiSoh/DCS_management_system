<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Student Profile</title>
  <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="container mx-auto p-6">
    <div class="bg-white rounded shadow p-6">
      <div class="flex items-center space-x-6">
        <div class="col-span-1">
          @if($user->profile_picture)
            <img src="{{ asset('storage/'.$user->profile_picture) }}" alt="Profile" class="h-24 w-24 rounded-full object-cover">
          @else
            <div class="h-24 w-24 rounded-full bg-gray-300 flex items-center justify-center text-2xl text-gray-600">{{ strtoupper(substr($user->name ?? 'U',0,1)) }}</div>
          @endif
        </div>

        <div class="flex-1">
          <h1 class="text-2xl font-semibold">{{ $user->name }}</h1>
          <div class="text-sm text-gray-600">{{ $user->email ?? '' }}</div>
          <div class="mt-3 text-sm text-gray-700">
            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
            <p><strong>Project:</strong> {{ $user->project ?? 'N/A' }}</p>
            <p><strong>Programme:</strong> {{ $user->programme ?? 'N/A' }}</p>
            <p class="mt-2"><strong>Bio:</strong> {{ $user->bio ?? 'No bio provided.' }}</p>
          </div>
        </div>

        <div class="ml-auto flex items-center space-x-2">
          @php
            $homeRouteName = auth()->check()
              ? (auth()->user()->role === 'supervisor' ? 'supervisor.dashboard' : (auth()->user()->role === 'committee' ? 'committee.dashboard' : 'dashboard'))
              : 'dashboard';
          @endphp
          <a href="{{ route($homeRouteName) }}" class="bg-gray-200 text-gray-800 px-3 py-2 rounded">Home</a>
          @auth
            @if(auth()->id() === $user->id)
              <a href="{{ route('profile.edit') }}" class="bg-blue-600 text-white px-4 py-2 rounded">Edit</a>
            @endif
          @endauth
        </div>
      </div>
    </div>
  </div>
</body>
</html>
