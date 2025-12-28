<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Finalize Grades - Committee</title>
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
            <h2 class="text-3xl font-bold text-gray-800">Finalize Grades</h2>
            <p class="text-gray-600 mt-2">Review and finalize final grades for FYP projects</p>
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

        <!-- Projects Table -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Project Title
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Student
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Supervisor
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Examiner
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Supervisor Grade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Examiner Grade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Final Grade
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($projects as $project)
                        <tr>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">{{ $project->title }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->user->name }}</div>
                                <div class="text-sm text-gray-500">{{ $project->user->email }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $project->supervisorUser->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->examiner_id && $project->examinerUser)
                                    <div class="text-sm text-gray-900">{{ $project->examinerUser->name }}</div>
                                @else
                                    <span class="text-sm text-gray-400">Not Assigned</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->finalGrade->supervisor_grade)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        {{ number_format($project->finalGrade->supervisor_grade, 2) }}
                                    </span>
                                    @if($project->finalGrade->supervisor_graded_at)
                                        <div class="text-xs text-gray-500 mt-1">
                                            {{ $project->finalGrade->supervisor_graded_at->format('M d, Y') }}
                                        </div>
                                    @endif
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->user->project === 'FYP I')
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500">
                                        N/A (FYP I)
                                    </span>
                                @else
                                    @if($project->finalGrade->examiner_grade)
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                            {{ number_format($project->finalGrade->examiner_grade, 2) }}
                                        </span>
                                        @if($project->finalGrade->examiner_graded_at)
                                            <div class="text-xs text-gray-500 mt-1">
                                                {{ $project->finalGrade->examiner_graded_at->format('M d, Y') }}
                                            </div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->finalGrade->final_grade)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                                        {{ number_format($project->finalGrade->final_grade, 2) }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600">
                                        Not Available
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($project->finalGrade->status === 'approved')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        Approved
                                    </span>
                                    <div class="text-xs text-gray-500 mt-1">
                                        {{ $project->finalGrade->finalized_at->format('M d, Y') }}
                                    </div>
                                @elseif($project->finalGrade->status === 'rejected')
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                        Rejected
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @if($project->finalGrade->status !== 'approved')
                                    @php
                                        $canReview = false;
                                        if ($project->user->project === 'FYP I') {
                                            // FYP I only needs supervisor grade
                                            $canReview = $project->finalGrade->supervisor_grade !== null;
                                        } else {
                                            // FYP II/Project needs both grades
                                            $canReview = $project->finalGrade->supervisor_grade !== null && $project->finalGrade->examiner_grade !== null;
                                        }
                                    @endphp
                                    
                                    @if($canReview)
                                        <a 
                                            href="{{ route('committee.grades.review', $project->id) }}"
                                            class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
                                            Review
                                        </a>
                                    @else
                                        @if($project->user->project === 'FYP I')
                                            <span class="text-gray-400 text-sm">Awaiting supervisor grade</span>
                                        @else
                                            <span class="text-gray-400 text-sm">Awaiting both grades</span>
                                        @endif
                                    @endif
                                @else
                                    <a 
                                        href="{{ route('committee.grades.review', $project->id) }}"
                                        class="inline-block bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">
                                        View
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                No graded projects found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Summary Statistics -->
        @if($projects->count() > 0)
            <div class="mt-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Total Projects</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ $projects->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Approved</h3>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $projects->filter(fn($p) => $p->finalGrade->status === 'approved')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Rejected</h3>
                    <p class="text-3xl font-bold text-red-600 mt-2">{{ $projects->filter(fn($p) => $p->finalGrade->status === 'rejected')->count() }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-sm font-medium text-gray-500">Pending</h3>
                    <p class="text-3xl font-bold text-yellow-600 mt-2">{{ $projects->filter(fn($p) => !$p->finalGrade->status)->count() }}</p>
                </div>
            </div>
        @endif
    </div>
</body>
</html>
