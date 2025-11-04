<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register Project</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <h1 class="text-2xl font-semibold mb-4">Register Project Title</h1>

        @if($errors->any())
          <div class="mb-4 text-red-600">
            <ul>
              @foreach($errors->all() as $e)
                <li>{{ $e }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form method="POST" action="{{ route('projects.store') }}">
          @csrf
          <div class="mb-4">
            <label class="block text-sm font-medium">Project Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="mt-1 block w-full border rounded p-2" required>
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium">Choose Supervisor</label>
            <select name="supervisor_id" class="mt-1 block w-full border rounded p-2" required>
              <option value="">-- choose --</option>
              @foreach($supervisors as $s)
                <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->email }})</option>
              @endforeach
            </select>
          </div>

          <div class="flex items-center space-x-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Register</button>
            <a href="{{ route('fyp.index') }}" class="bg-gray-200 px-4 py-2 rounded">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
