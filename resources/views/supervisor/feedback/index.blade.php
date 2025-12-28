<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Submissions Feedback - FYP Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Final Year Project - Supervisor</h1>
            <div class="flex gap-4">
                <a href="{{ route('supervisor.dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                    Dashboard
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
            <h2 class="text-3xl font-bold text-gray-800">Student Submissions - Provide Feedback</h2>
            <p class="text-gray-600 mt-2">Review student project submissions and provide feedback</p>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Submissions List -->
        <div class="space-y-4">
            @forelse($submissions as $submission)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="p-6">
                        <!-- Task Info and Submission Details -->
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <h3 class="text-xl font-semibold text-gray-900">
                                    {{ $submission->title }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">
                                    <strong>Student:</strong> {{ $submission->student->name }} ({{ $submission->student->email }})
                                </p>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($submission->priority === 'high') bg-red-100 text-red-800
                                        @elseif($submission->priority === 'medium') bg-yellow-100 text-yellow-800
                                        @else bg-green-100 text-green-800
                                        @endif">
                                        {{ ucfirst($submission->priority) }} Priority
                                    </span>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ ucfirst(str_replace('_', ' ', $submission->status)) }}
                                    </span>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-500">Submitted:</p>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $submission->submitted_at ? $submission->submitted_at->format('h:i A') : '' }}
                                </p>
                            </div>
                        </div>

                        <!-- Task Description -->
                        @if($submission->description)
                            <div class="mb-4 p-3 bg-gray-50 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-700 mb-1">Task Description:</h4>
                                <p class="text-sm text-gray-700">{{ $submission->description }}</p>
                            </div>
                        @endif

                        <!-- Submitted File -->
                        @if($submission->submission_file)
                            <div class="mb-4 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                <h4 class="text-sm font-semibold text-gray-700 mb-2">Submitted File:</h4>
                                <a href="{{ asset('storage/' . $submission->submission_file) }}" 
                                   target="_blank"
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm rounded hover:bg-blue-700 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    Download Submission
                                </a>
                            </div>
                        @endif

                        <!-- Feedback Form -->
                        <form method="POST" action="{{ route('supervisor.feedback.store', $submission->id) }}" class="mt-4">
                            @csrf
                            <div>
                                <label for="feedback_{{ $submission->id }}" class="block text-sm font-medium text-gray-700 mb-2">
                                    Supervisor Feedback
                                    @if($submission->feedback)
                                        <span class="text-green-600 text-xs">(Previously provided)</span>
                                    @endif
                                </label>
                                <div class="flex gap-2">
                                    <textarea 
                                        name="feedback" 
                                        id="feedback_{{ $submission->id }}" 
                                        rows="4" 
                                        class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Enter your feedback for the student's submission..."
                                        required>{{ $submission->feedback }}</textarea>
                                    <button 
                                        type="submit" 
                                        class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium self-end">
                                        {{ $submission->feedback ? 'Update' : 'Save' }}
                                    </button>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">
                                    Provide constructive feedback to help the student improve their work.
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions found</h3>
                    <p class="mt-1 text-sm text-gray-500">Your supervised students haven't submitted their project materials yet.</p>
                </div>
            @endforelse
        </div>

        <!-- Summary Statistics -->
        @if($submissions->count() > 0)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Submissions</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $submissions->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">With Feedback</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $submissions->filter(fn($s) => $s->feedback)->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Pending Feedback</h3>
                    <p class="text-3xl font-bold text-orange-600 mt-2">{{ $submissions->filter(fn($s) => !$s->feedback)->count() }}</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
