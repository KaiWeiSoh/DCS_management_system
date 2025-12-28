<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Submission - Student</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Final Year Project - Student</h1>
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                    Home
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600 transition">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Task Submission</h2>
            <p class="text-gray-600 mt-2">Submit and manage your FYP tasks</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Tasks List -->
        <div class="space-y-4">
            @forelse($tasks as $task)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold text-gray-800">{{ $task->title }}</h3>
                            <p class="text-sm text-gray-500 mt-1">
                                Assigned by: <span class="font-medium">{{ $task->supervisor->name }}</span>
                            </p>
                        </div>
                        <div class="flex gap-2">
                            <!-- Priority Badge -->
                            @if($task->priority === 'high')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    High Priority
                                </span>
                            @elseif($task->priority === 'medium')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Medium Priority
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Low Priority
                                </span>
                            @endif

                            <!-- Status Badge -->
                            @if($task->status === 'completed')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Completed
                                </span>
                            @elseif($task->status === 'in_progress')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    In Progress
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                    Pending
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($task->description)
                        <div class="mb-4">
                            <p class="text-gray-700">{{ $task->description }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div>
                            <span class="text-gray-600">Due Date:</span>
                            <span class="font-medium text-gray-900">
                                {{ $task->due_date ? $task->due_date->format('M d, Y') : 'No deadline' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-gray-600">Assigned:</span>
                            <span class="font-medium text-gray-900">{{ $task->created_at->format('M d, Y') }}</span>
                        </div>
                    </div>

                    @if($task->submitted_at)
                        <!-- Already Submitted -->
                        <div class="border-t pt-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="font-medium text-green-800">Submitted on {{ $task->submitted_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @if($task->submission_file)
                                    <a href="{{ asset('storage/' . $task->submission_file) }}" 
                                       target="_blank"
                                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 text-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                        {{ basename($task->submission_file) }}
                                    </a>
                                @endif
                            </div>

                            <!-- Supervisor Feedback -->
                            @if($task->feedback)
                                <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                                    <div class="flex items-start">
                                        <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        <div class="flex-1">
                                            <h4 class="text-sm font-semibold text-blue-900 mb-2">Supervisor's Feedback</h4>
                                            <div class="text-sm text-gray-800 whitespace-pre-wrap bg-white p-3 rounded border border-blue-200">{{ $task->feedback }}</div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Submission Form -->
                        <div class="border-t pt-4">
                            <form method="POST" action="{{ route('tasks.submit', $task->id) }}" enctype="multipart/form-data" class="flex items-end gap-4">
                                @csrf
                                <div class="flex-1">
                                    <label for="submission_file_{{ $task->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Submission File
                                    </label>
                                    <input 
                                        type="file" 
                                        name="submission_file" 
                                        id="submission_file_{{ $task->id }}" 
                                        accept=".pdf,.doc,.docx,.zip"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        required
                                    >
                                    <p class="mt-1 text-xs text-gray-500">Accepted formats: PDF, DOC, DOCX, ZIP (Max: 10MB)</p>
                                    @error('submission_file')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                                <button 
                                    type="submit" 
                                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium">
                                    Submit
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No tasks assigned yet</h3>
                        <p class="mt-1 text-sm text-gray-500">Your supervisor will assign tasks that will appear here.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</body>
</html>
