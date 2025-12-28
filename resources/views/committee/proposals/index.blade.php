<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submitted FYP Proposals</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Final Year Project - Committee</h1>
            <div class="flex gap-4">
                <a href="{{ route('committee.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded hover:bg-gray-100 transition">
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
            <h2 class="text-3xl font-bold text-gray-800">Submitted FYP Proposals</h2>
            <p class="text-gray-600 mt-2">View and download student project submissions</p>
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

        <!-- Submissions Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            @if($submissions->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student Name
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Project Type
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Programme
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Submitted Date
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Downloads
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($submissions as $submission)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $submission->project->user->name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ $submission->project->user->email }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            @if($submission->project->user->project === 'FYP I') bg-blue-100 text-blue-800
                                            @elseif($submission->project->user->project === 'FYP II') bg-green-100 text-green-800
                                            @else bg-purple-100 text-purple-800
                                            @endif">
                                            {{ $submission->project->user->project }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-sm text-gray-900">
                                            {{ $submission->project->user->programme ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $submission->submitted_at ? $submission->submitted_at->format('M d, Y') : 'N/A' }}
                                        @if($submission->submitted_at)
                                            <div class="text-xs text-gray-400">
                                                {{ $submission->submitted_at->format('h:i A') }}
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-2">
                                            @if($submission->project->user->project === 'FYP I')
                                                <!-- FYP I: Only Report -->
                                                @if($submission->report)
                                                    <a href="{{ asset('storage/' . $submission->report) }}" 
                                                       target="_blank"
                                                       class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Report
                                                    </a>
                                                @else
                                                    <span class="text-xs text-gray-400">No report</span>
                                                @endif
                                            @else
                                                <!-- FYP II/Project: Abstract, Extended Abstract, Source Code, Report, Video -->
                                                <div class="grid grid-cols-2 gap-2">
                                                    @if($submission->abstract)
                                                        <a href="{{ asset('storage/' . $submission->abstract) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center justify-center px-2 py-1.5 bg-indigo-600 text-white text-xs rounded hover:bg-indigo-700 transition">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                            Abstract
                                                        </a>
                                                    @endif
                                                    
                                                    @if($submission->extended_abstract)
                                                        <a href="{{ asset('storage/' . $submission->extended_abstract) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center justify-center px-2 py-1.5 bg-yellow-500 text-white text-xs rounded hover:bg-yellow-600 transition">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                            Ext. Abstract
                                                        </a>
                                                    @endif
                                                    
                                                    @if($submission->source_code)
                                                        <a href="{{ asset('storage/' . $submission->source_code) }}" 
                                                           download
                                                           class="inline-flex items-center justify-center px-2 py-1.5 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                                                            </svg>
                                                            Source Code
                                                        </a>
                                                    @endif
                                                    
                                                    @if($submission->report)
                                                        <a href="{{ asset('storage/' . $submission->report) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center justify-center px-2 py-1.5 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                            Report
                                                        </a>
                                                    @endif
                                                    
                                                    @if($submission->presentation_video)
                                                        <a href="{{ asset('storage/' . $submission->presentation_video) }}" 
                                                           target="_blank"
                                                           class="inline-flex items-center justify-center px-2 py-1.5 bg-purple-600 text-white text-xs rounded hover:bg-purple-700 transition col-span-2">
                                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                                                            </svg>
                                                            Presentation Video
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No submissions found</h3>
                    <p class="mt-1 text-sm text-gray-500">Students haven't submitted their project materials yet.</p>
                </div>
            @endif
        </div>

        <!-- Summary Statistics -->
        @if($submissions->count() > 0)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Submissions</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $submissions->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">FYP I</h3>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $submissions->filter(fn($s) => $s->project->user->project === 'FYP I')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">FYP II</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $submissions->filter(fn($s) => $s->project->user->project === 'FYP II')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Project</h3>
                    <p class="text-3xl font-bold text-purple-600 mt-2">{{ $submissions->filter(fn($s) => $s->project->user->project === 'Project')->count() }}</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
