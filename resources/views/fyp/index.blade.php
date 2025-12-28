<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Final Year Project</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <div class="flex items-center justify-between mb-4">
          <h1 class="text-2xl font-semibold">Final Year Project</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('dashboard') }}" class="text-sm text-gray-700">Back</a>
          </div>
        </div>

        @if(session('success'))
          <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        @if(session('error'))
          <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-700 rounded">{{ session('error') }}</div>
        @endif

        @if(!$project)
          <div class="p-4 border rounded bg-gray-50">
            <p>You don't have a registered project yet. Please register a project title and choose a supervisor.</p>
            <a href="{{ route('projects.create') }}" class="inline-block mt-3 bg-blue-600 text-white px-4 py-2 rounded">Register Project</a>
          </div>
        @else
          <div class="mb-4">
            <div class="text-lg font-semibold">Project: {{ $project->title }}</div>
            <div class="text-sm text-gray-600">Supervisor: {{ $project->supervisor ?? 'Not assigned' }}</div>
            <div class="text-sm mt-2">
              <strong>Approval status:</strong>
              @if(is_null($project->approved))
                <span class="text-yellow-600">Pending</span>
              @elseif($project->approved)
                <span class="text-green-600">Approved</span>
              @else
                <span class="text-red-600">Refused</span>
              @endif
            </div>
          </div>

          <!-- Project Resubmission Form (Only shown when Refused) -->
          @if($project->approved === false || $project->approved === 0)
            <div class="mt-6 p-6 bg-red-50 border-2 border-red-200 rounded-lg">
              <div class="flex items-start mb-4">
                <svg class="w-6 h-6 text-red-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <div>
                  <h3 class="text-lg font-semibold text-red-900">Your project has been rejected</h3>
                  <p class="text-sm text-red-800 mt-1">You can resubmit your project with a new title for review by the committee.</p>
                </div>
              </div>

              <form method="POST" action="{{ route('projects.resubmit', $project->id) }}" class="mt-4">
                @csrf
                <div class="mb-4">
                  <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                    New Project Title <span class="text-red-500">*</span>
                  </label>
                  <input 
                    type="text" 
                    id="title" 
                    name="title" 
                    value="{{ old('title') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Enter your revised project title..."
                    required
                  >
                  @error('title')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                  @enderror
                  <p class="mt-2 text-xs text-gray-600">
                    <strong>Note:</strong> Your supervisor assignment will remain the same. The committee will review your new project title.
                  </p>
                </div>

                <div class="flex items-center gap-3">
                  <button 
                    type="submit" 
                    class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                    Resubmit Project
                  </button>
                  <p class="text-xs text-gray-600">This will reset your approval status to "Pending"</p>
                </div>
              </form>
            </div>
          @endif

          <div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded">
            <p class="text-sm text-gray-700">For final report submission, please go to the <strong>Final Report</strong> section from the dashboard menu.</p>
          </div>
        @endif
      </div>
    </div>
  </body>
</html>
