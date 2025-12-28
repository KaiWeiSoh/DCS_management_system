<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Submit Report</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6 max-w-xl mx-auto">
        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl font-semibold">Submit Report</h1>
          <a href="{{ route('fyp.index') }}" class="text-sm text-gray-700">Back</a>
        </div>
        @if(session('error'))
          <div class="mb-4 text-red-600">{{ session('error') }}</div>
        @endif
        <form method="POST" action="{{ route('reports.store') }}" enctype="multipart/form-data" class="space-y-4">
          @csrf
          <div>
            <label class="block text-sm font-medium mb-1">Title</label>
            <input type="text" name="title" value="{{ old('title') }}" class="w-full border rounded px-3 py-2 focus:outline-none focus:ring" required>
            @error('title')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div>
            <label class="block text-sm font-medium mb-1">Upload File (optional)</label>
            <input type="file" name="file" class="w-full" accept="application/pdf,.doc,.docx,.ppt,.pptx">
            @error('file')<div class="text-xs text-red-600 mt-1">{{ $message }}</div>@enderror
          </div>
          <div class="flex items-center justify-end space-x-2">
            <a href="{{ route('fyp.index') }}" class="px-4 py-2 text-sm rounded border">Cancel</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Submit</button>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
