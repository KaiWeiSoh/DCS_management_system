<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Create Milestone</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl font-semibold">Create Milestone for {{ $project->title }}</h1>
          <a href="{{ route('milestones.index') }}" class="text-sm text-gray-700">Back</a>
        </div>

        <form method="POST" action="{{ route('milestones.store') }}">
          @csrf
          <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-sm font-medium">Title</label>
              <input type="text" name="title" class="mt-1 block w-full border rounded p-2" required>
            </div>
            <div>
              <label class="block text-sm font-medium">Description</label>
              <textarea name="description" rows="4" class="mt-1 block w-full border rounded p-2"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium">Priority (number)</label>
              <input type="number" name="priority" value="0" class="mt-1 block w-full border rounded p-2">
            </div>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium">Start (optional)</label>
                <input type="datetime-local" name="start_at" class="mt-1 block w-full border rounded p-2">
              </div>
              <div>
                <label class="block text-sm font-medium">End (optional)</label>
                <input type="datetime-local" name="end_at" class="mt-1 block w-full border rounded p-2">
              </div>
            </div>
          </div>

          <div class="mt-4 flex items-center space-x-3">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Create</button>
            <a href="{{ route('milestones.index') }}" class="bg-gray-200 px-4 py-2 rounded">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
