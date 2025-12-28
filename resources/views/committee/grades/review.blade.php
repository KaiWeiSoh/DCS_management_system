<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Grade - Committee</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .rubric-section {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            margin-bottom: 0.5rem;
        }
        .rubric-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 0.5rem;
            margin-bottom: 0.25rem;
            border-radius: 0.25rem;
            background: #f9fafb;
        }
        .rubric-remark {
            font-size: 0.75rem;
            color: #6b7280;
            font-style: italic;
            margin-left: 0.5rem;
            margin-top: 0.25rem;
        }
    </style>
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
    <div class="max-w-6xl mx-auto px-6 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('committee.grades.index') }}" class="text-green-600 hover:text-green-800 flex items-center gap-2 mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Grades List
            </a>
            <h2 class="text-3xl font-bold text-gray-800">Review Final Grade</h2>
            <p class="text-gray-600 mt-2">Review project details and final grade before finalizing</p>
        </div>

        <!-- Project Details Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">Project Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Project Title</p>
                    <p class="font-medium text-gray-900 text-lg">{{ $project->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Student</p>
                    <p class="font-medium text-gray-900">{{ $project->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $project->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Supervisor</p>
                    <p class="font-medium text-gray-900">{{ $project->supervisorUser->name ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-500">{{ $project->supervisorUser->email ?? '' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Examiner</p>
                    @if($project->examiner_id && $project->examinerUser)
                        <p class="font-medium text-gray-900">{{ $project->examinerUser->name }}</p>
                        <p class="text-sm text-gray-500">{{ $project->examinerUser->email }}</p>
                    @else
                        <p class="font-medium text-gray-400">Not Assigned</p>
                    @endif
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Grade Assigned Date</p>
                    <p class="font-medium text-gray-900">{{ $finalGrade->created_at->format('M d, Y h:i A') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Grade Assigned By</p>
                    <p class="font-medium text-gray-900">{{ $finalGrade->supervisor->name }}</p>
                    @if($project->supervisor_id === $finalGrade->supervisor_id)
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-medium bg-blue-100 text-blue-800">
                            Supervisor
                        </span>
                    @elseif($project->examiner_id === $finalGrade->supervisor_id)
                        <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded text-xs font-medium bg-purple-100 text-purple-800">
                            Examiner
                        </span>
                    @endif
                </div>
            </div>

            @if($project->description)
                <div class="mt-6">
                    <p class="text-sm text-gray-600 mb-2">Project Description</p>
                    <p class="text-gray-800 bg-gray-50 p-4 rounded">{{ $project->description }}</p>
                </div>
            @endif
        </div>

        <!-- Supervisor and Examiner Rubric Details -->
        @if(($finalGrade->supervisor_rubric_data && is_array($finalGrade->supervisor_rubric_data)) || ($finalGrade->examiner_rubric_data && is_array($finalGrade->examiner_rubric_data)))
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-6 border-b pb-2 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Detailed Grade Assessment
            </h3>
            
            <div class="grid grid-cols-1 {{ $project->user->project === 'FYP I' ? '' : 'lg:grid-cols-2' }} gap-6">
                <!-- Supervisor Rubric -->
                <div>
                    <a href="{{ route('committee.grades.rubric.supervisor', $project->id) }}" class="block bg-green-50 border-2 border-green-300 rounded-lg p-5 mb-4 hover:shadow-lg hover:border-green-400 transition-all duration-200 cursor-pointer">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-bold text-green-800 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Supervisor's Assessment
                            </h4>
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        
                        @if($finalGrade->supervisor_rubric_data && is_array($finalGrade->supervisor_rubric_data))
                            <!-- Grade Summary -->
                            <div class="bg-white rounded-lg p-4 mb-4 border-2 border-green-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600 font-medium">Final Grade</span>
                                    <span class="text-3xl font-bold text-green-700">{{ number_format($finalGrade->supervisor_grade, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs text-gray-500 pt-2 border-t border-gray-200">
                                    <span>Project Type:</span>
                                    <span class="font-semibold">{{ $finalGrade->supervisor_rubric_data['type'] ?? 'N/A' }}</span>
                                </div>
                                @if($finalGrade->supervisor_graded_at)
                                <div class="flex justify-between items-center text-xs text-gray-500 mt-1">
                                    <span>Graded On:</span>
                                    <span class="font-semibold">{{ $finalGrade->supervisor_graded_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                                @if($finalGrade->updated_at && $finalGrade->supervisor_graded_at && $finalGrade->updated_at->gt($finalGrade->supervisor_graded_at->addMinute()))
                                <div class="flex items-center gap-1 text-xs text-orange-600 mt-2 bg-orange-50 px-2 py-1 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Updated: {{ $finalGrade->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Rubric Breakdown -->
                            @php
                                $supervisorData = $finalGrade->supervisor_rubric_data;
                                $projectType = $supervisorData['type'] ?? '';
                            @endphp

                            @if($projectType === 'FYP II' || $projectType === 'Project')
                                <!-- FYP II/Project Sections -->
                                <div class="space-y-3">
                                    <!-- Technical Section -->
                                    @if(isset($supervisorData['technical']))
                                    <div class="bg-white rounded p-3 border border-green-200">
                                        <h5 class="font-bold text-green-800 text-sm mb-2 flex items-center gap-1">
                                            <span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded">60%</span>
                                            Technical Development
                                        </h5>
                                        <div class="space-y-1 text-xs">
                                            @php
                                                $techLabels = [
                                                    'functionality' => 'Functionality & Features',
                                                    'technical_quality' => 'Technical Quality',
                                                    'innovation' => 'Innovation & Creativity',
                                                    'effort_supervisor' => 'Effort & Contribution'
                                                ];
                                            @endphp
                                            @foreach($techLabels as $key => $label)
                                                @if(isset($supervisorData['technical'][$key]))
                                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <span class="font-bold text-green-700">{{ number_format($supervisorData['technical'][$key], 1) }}/10</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Report Section -->
                                    @if(isset($supervisorData['report']))
                                    <div class="bg-white rounded p-3 border border-green-200">
                                        <h5 class="font-bold text-green-800 text-sm mb-2 flex items-center gap-1">
                                            <span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded">20%</span>
                                            Final Report
                                        </h5>
                                        <div class="space-y-1 text-xs">
                                            @php
                                                $reportLabels = [
                                                    'content' => 'Content & Structure',
                                                    'analysis' => 'Analysis & Discussion'
                                                ];
                                            @endphp
                                            @foreach($reportLabels as $key => $label)
                                                @if(isset($supervisorData['report'][$key]))
                                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <span class="font-bold text-green-700">{{ number_format($supervisorData['report'][$key], 1) }}/10</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Presentation Section -->
                                    @if(isset($supervisorData['presentation']))
                                    <div class="bg-white rounded p-3 border border-green-200">
                                        <h5 class="font-bold text-green-800 text-sm mb-2 flex items-center gap-1">
                                            <span class="bg-green-600 text-white text-xs px-2 py-0.5 rounded">20%</span>
                                            Presentation
                                        </h5>
                                        <div class="space-y-1 text-xs">
                                            @php
                                                $presentationLabels = [
                                                    'delivery' => 'Delivery & Communication',
                                                    'content_presentation' => 'Content & Organization'
                                                ];
                                            @endphp
                                            @foreach($presentationLabels as $key => $label)
                                                @if(isset($supervisorData['presentation'][$key]))
                                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <span class="font-bold text-green-700">{{ number_format($supervisorData['presentation'][$key], 1) }}/10</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Comments -->
                            @if($finalGrade->supervisor_comments)
                                <div class="mt-4 bg-white p-3 rounded border border-green-200">
                                    <p class="text-xs font-semibold text-gray-600 mb-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        Supervisor Comments:
                                    </p>
                                    <p class="text-sm text-gray-800 leading-relaxed">{{ $finalGrade->supervisor_comments }}</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 italic">No assessment data available</p>
                            </div>
                        @endif
                        
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center gap-2 text-sm text-green-700 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Click to view full rubric details
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Examiner Rubric (Only for FYP II/Project) -->
                @if($project->user->project !== 'FYP I')
                <div>
                    <a href="{{ route('committee.grades.rubric.examiner', $project->id) }}" class="block bg-purple-50 border-2 border-purple-300 rounded-lg p-5 mb-4 hover:shadow-lg hover:border-purple-400 transition-all duration-200 cursor-pointer">
                        <div class="flex items-center justify-between mb-4">
                            <h4 class="text-lg font-bold text-purple-800 flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                                Examiner's Assessment
                            </h4>
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </div>
                        
                        @if($finalGrade->examiner_rubric_data && is_array($finalGrade->examiner_rubric_data))
                            <!-- Grade Summary -->
                            <div class="bg-white rounded-lg p-4 mb-4 border-2 border-purple-200">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-sm text-gray-600 font-medium">Final Grade</span>
                                    <span class="text-3xl font-bold text-purple-700">{{ number_format($finalGrade->examiner_grade, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-xs text-gray-500 pt-2 border-t border-gray-200">
                                    <span>Project Type:</span>
                                    <span class="font-semibold">{{ $finalGrade->examiner_rubric_data['type'] ?? 'N/A' }}</span>
                                </div>
                                @if($finalGrade->examiner_graded_at)
                                <div class="flex justify-between items-center text-xs text-gray-500 mt-1">
                                    <span>Graded On:</span>
                                    <span class="font-semibold">{{ $finalGrade->examiner_graded_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                                @if($finalGrade->updated_at && $finalGrade->examiner_graded_at && $finalGrade->updated_at->gt($finalGrade->examiner_graded_at->addMinute()))
                                <div class="flex items-center gap-1 text-xs text-orange-600 mt-2 bg-orange-50 px-2 py-1 rounded">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Updated: {{ $finalGrade->updated_at->format('M d, Y h:i A') }}</span>
                                </div>
                                @endif
                            </div>

                            <!-- Rubric Breakdown -->
                            @php
                                $examinerData = $finalGrade->examiner_rubric_data;
                                $projectType = $examinerData['type'] ?? '';
                            @endphp

                            @if($projectType === 'FYP II' || $projectType === 'Project')
                                <!-- FYP II/Project Sections -->
                                <div class="space-y-3">
                                    <!-- Technical Section -->
                                    @if(isset($examinerData['technical']))
                                    <div class="bg-white rounded p-3 border border-purple-200">
                                        <h5 class="font-bold text-purple-800 text-sm mb-2 flex items-center gap-1">
                                            <span class="bg-purple-600 text-white text-xs px-2 py-0.5 rounded">60%</span>
                                            Technical Development
                                        </h5>
                                        <div class="space-y-1 text-xs">
                                            @php
                                                $techLabels = [
                                                    'functionality' => 'Functionality & Features',
                                                    'technical_quality' => 'Technical Quality',
                                                    'innovation' => 'Innovation & Creativity',
                                                    'effort_examiner' => 'Effort & Contribution'
                                                ];
                                            @endphp
                                            @foreach($techLabels as $key => $label)
                                                @if(isset($examinerData['technical'][$key]))
                                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <span class="font-bold text-purple-700">{{ number_format($examinerData['technical'][$key], 1) }}/10</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Report Section -->
                                    @if(isset($examinerData['report']))
                                    <div class="bg-white rounded p-3 border border-purple-200">
                                        <h5 class="font-bold text-purple-800 text-sm mb-2 flex items-center gap-1">
                                            <span class="bg-purple-600 text-white text-xs px-2 py-0.5 rounded">20%</span>
                                            Final Report
                                        </h5>
                                        <div class="space-y-1 text-xs">
                                            @php
                                                $reportLabels = [
                                                    'content' => 'Content & Structure',
                                                    'analysis' => 'Analysis & Discussion'
                                                ];
                                            @endphp
                                            @foreach($reportLabels as $key => $label)
                                                @if(isset($examinerData['report'][$key]))
                                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <span class="font-bold text-purple-700">{{ number_format($examinerData['report'][$key], 1) }}/10</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif

                                    <!-- Presentation Section -->
                                    @if(isset($examinerData['presentation']))
                                    <div class="bg-white rounded p-3 border border-purple-200">
                                        <h5 class="font-bold text-purple-800 text-sm mb-2 flex items-center gap-1">
                                            <span class="bg-purple-600 text-white text-xs px-2 py-0.5 rounded">20%</span>
                                            Presentation
                                        </h5>
                                        <div class="space-y-1 text-xs">
                                            @php
                                                $presentationLabels = [
                                                    'delivery' => 'Delivery & Communication',
                                                    'content_presentation' => 'Content & Organization'
                                                ];
                                            @endphp
                                            @foreach($presentationLabels as $key => $label)
                                                @if(isset($examinerData['presentation'][$key]))
                                                <div class="flex justify-between items-center py-1 border-b border-gray-100">
                                                    <span class="text-gray-700">{{ $label }}</span>
                                                    <span class="font-bold text-purple-700">{{ number_format($examinerData['presentation'][$key], 1) }}/10</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Comments -->
                            @if($finalGrade->examiner_comments)
                                <div class="mt-4 bg-white p-3 rounded border border-purple-200">
                                    <p class="text-xs font-semibold text-gray-600 mb-2 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                        </svg>
                                        Examiner Comments:
                                    </p>
                                    <p class="text-sm text-gray-800 leading-relaxed">{{ $finalGrade->examiner_comments }}</p>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-8">
                                <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-gray-500 italic">No assessment data available</p>
                            </div>
                        @endif
                        
                        <div class="mt-4 text-center">
                            <span class="inline-flex items-center gap-2 text-sm text-purple-700 font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Click to view full rubric details
                            </span>
                        </div>
                    </a>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Final Grade Calculation Summary -->
        @if($project->user->project !== 'FYP I')
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg shadow-md p-6 mb-6 border-2 border-blue-200">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    Final Course Grade Calculation
                </h3>
                
                @php
                    $supervisorGrandTotal = $finalGrade->supervisor_grade ?? 0;
                    $examinerGrandTotal = $finalGrade->examiner_grade ?? 0;
                    $supervisorWeighted = $supervisorGrandTotal * 0.6;
                    $examinerWeighted = $examinerGrandTotal * 0.4;
                    $finalCourseGrade = $supervisorWeighted + $examinerWeighted;
                @endphp
                
                <div class="bg-white rounded-lg p-6 mb-4 shadow-sm">
                    <h4 class="font-semibold text-gray-700 mb-4">Calculation Breakdown:</h4>
                    
                    <div class="space-y-4">
                        <!-- Supervisor Contribution -->
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg border border-green-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">
                                    S
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Supervisor Grand Total</p>
                                    <p class="text-lg font-bold text-gray-800">{{ number_format($supervisorGrandTotal, 2) }} / 100</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">× 60%</p>
                                <p class="text-xl font-bold text-green-700">= {{ number_format($supervisorWeighted, 2) }}</p>
                            </div>
                        </div>
                        
                        <!-- Plus Sign -->
                        <div class="text-center text-2xl font-bold text-gray-400">+</div>
                        
                        <!-- Examiner Contribution -->
                        <div class="flex items-center justify-between p-4 bg-purple-50 rounded-lg border border-purple-200">
                            <div class="flex items-center gap-3">
                                <div class="bg-purple-600 text-white rounded-full w-10 h-10 flex items-center justify-center font-bold">
                                    E
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Examiner Grand Total</p>
                                    <p class="text-lg font-bold text-gray-800">{{ number_format($examinerGrandTotal, 2) }} / 100</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm text-gray-600">× 40%</p>
                                <p class="text-xl font-bold text-purple-700">= {{ number_format($examinerWeighted, 2) }}</p>
                            </div>
                        </div>
                        
                        <!-- Divider -->
                        <div class="border-t-2 border-gray-300 my-4"></div>
                        
                        <!-- Final Result -->
                        <div class="flex items-center justify-between p-5 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-lg border-2 border-blue-400">
                            <div class="flex items-center gap-3">
                                <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-700 font-medium">Final Course Grade</p>
                                    <p class="text-xs text-gray-600">({{ number_format($supervisorWeighted, 2) }} + {{ number_format($examinerWeighted, 2) }})</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-3xl font-bold text-blue-700">{{ number_format($finalCourseGrade, 2) }}</p>
                                <p class="text-sm text-gray-600">out of 100</p>
                            </div>
                        </div>
                        
                        <!-- Grade Classification -->
                        @php
                            if($finalCourseGrade >= 80) {
                                $classification = 'First Class';
                                $classColor = 'text-green-700 bg-green-100 border-green-300';
                            } elseif($finalCourseGrade >= 65) {
                                $classification = 'Upper Second Class';
                                $classColor = 'text-blue-700 bg-blue-100 border-blue-300';
                            } elseif($finalCourseGrade >= 50) {
                                $classification = 'Lower Second Class';
                                $classColor = 'text-yellow-700 bg-yellow-100 border-yellow-300';
                            } elseif($finalCourseGrade >= 40) {
                                $classification = 'Third Class';
                                $classColor = 'text-orange-700 bg-orange-100 border-orange-300';
                            } else {
                                $classification = 'Fail';
                                $classColor = 'text-red-700 bg-red-100 border-red-300';
                            }
                        @endphp
                        
                        <div class="text-center mt-4">
                            <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold border-2 {{ $classColor }}">
                                {{ $classification }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-800">
                        <strong>📝 Note:</strong> The final course grade is calculated using a weighted average formula: 
                        <span class="font-mono bg-white px-2 py-1 rounded">Final Grade = (Supervisor × 0.6) + (Examiner × 0.4)</span>
                    </p>
                </div>
            </div>
        @endif

        <!-- Decision Section -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">Committee Decision</h3>
            
            @php
                $canFinalize = false;
                if ($project->user->project === 'FYP I') {
                    // FYP I only needs supervisor grade
                    $canFinalize = $finalGrade->supervisor_grade !== null;
                } else {
                    // FYP II/Project needs both grades
                    $canFinalize = $finalGrade->supervisor_grade !== null && $finalGrade->examiner_grade !== null;
                }
            @endphp
            
            @if($finalGrade->status === 'approved')
                <!-- Approved Status Message -->
                <div class="bg-green-50 border-2 border-green-400 rounded-lg p-6 mb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-green-800 mb-2">Grade Successfully Finalized</h4>
                            <p class="text-green-700 mb-3">
                                This grade has been approved and finalized by the committee on 
                                <strong>{{ $finalGrade->finalized_at->format('F d, Y \\a\\t h:i A') }}</strong>
                            </p>
                            <div class="bg-white rounded p-4 border border-green-200">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-gray-600">Final Course Grade:</span>
                                    <span class="text-3xl font-bold text-green-700">{{ number_format($finalGrade->final_grade, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @elseif($finalGrade->status === 'rejected')
                <!-- Rejected Status Message -->
                <div class="bg-red-50 border-2 border-red-400 rounded-lg p-6 mb-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-red-800 mb-2">Grade Rejected</h4>
                            <p class="text-red-700 mb-3">
                                This grade has been rejected by the committee. The supervisor and examiner have been notified to review and assign a new grade.
                            </p>
                            <div class="bg-white rounded p-4 border border-red-200">
                                <p class="text-sm text-gray-700">
                                    <svg class="w-5 h-5 inline text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <strong>Note:</strong> The supervisor and examiner can now edit and resubmit the grades for this project.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Pending - Show Decision Buttons -->
                @if(!$canFinalize)
                    <div class="bg-yellow-50 border border-yellow-300 rounded-lg p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-6 h-6 text-yellow-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <div>
                                <p class="font-semibold text-yellow-800 mb-1">Awaiting Complete Grading</p>
                                @if($project->user->project === 'FYP I')
                                    <p class="text-sm text-yellow-700">
                                        This FYP I project cannot be finalized yet. The Supervisor must submit their grade before the Committee can finalize.
                                    </p>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-xs">
                                            <span class="font-medium">Supervisor:</span> 
                                            @if($finalGrade->supervisor_grade)
                                                <span class="text-green-600">✓ Graded ({{ number_format($finalGrade->supervisor_grade, 2) }})</span>
                                            @else
                                                <span class="text-red-600">✗ Not yet graded</span>
                                            @endif
                                        </p>
                                    </div>
                                @else
                                    <p class="text-sm text-yellow-700">
                                        This project cannot be finalized yet. Both Supervisor and Examiner must submit their grades before the Committee can finalize.
                                    </p>
                                    <div class="mt-2 space-y-1">
                                        <p class="text-xs">
                                            <span class="font-medium">Supervisor:</span> 
                                            @if($finalGrade->supervisor_grade)
                                                <span class="text-green-600">✓ Graded ({{ number_format($finalGrade->supervisor_grade, 2) }})</span>
                                            @else
                                                <span class="text-red-600">✗ Not yet graded</span>
                                            @endif
                                        </p>
                                        <p class="text-xs">
                                            <span class="font-medium">Examiner:</span> 
                                            @if($finalGrade->examiner_grade)
                                                <span class="text-green-600">✓ Graded ({{ number_format($finalGrade->examiner_grade, 2) }})</span>
                                            @else
                                                <span class="text-red-600">✗ Not yet graded</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @else
                    <p class="text-gray-600 mb-6">Please review the grade and make a decision to either finalize or reject it.</p>
                @endif
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <!-- Finalize Button -->
                    <form method="POST" action="{{ route('committee.grades.finalize', $project->id) }}" class="flex-1">
                        @csrf
                        <button 
                            type="submit" 
                            onclick="return confirm('Are you sure you want to finalize this grade? This action cannot be undone.')"
                            class="w-full px-6 py-3 rounded-lg transition font-medium flex items-center justify-center gap-2 {{ $canFinalize ? 'bg-green-600 text-white hover:bg-green-700' : 'bg-gray-300 text-gray-500 cursor-not-allowed' }}"
                            {{ !$canFinalize ? 'disabled' : '' }}>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Finalize Grade
                        </button>
                    </form>

                    <!-- Reject Button -->
                    <form method="POST" action="{{ route('committee.grades.reject', $project->id) }}" class="flex-1">
                        @csrf
                        <button 
                            type="submit" 
                            onclick="return confirm('Are you sure you want to reject this grade? The supervisor will be notified to review and assign a new grade.')"
                            class="w-full bg-red-600 text-white px-6 py-3 rounded-lg hover:bg-red-700 transition font-medium flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                            Reject Grade
                        </button>
                    </form>
                </div>
            @endif

            <div class="mt-4">
                <a 
                    href="{{ route('committee.grades.index') }}" 
                    class="block text-center bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                    Back to Grades List
                </a>
            </div>
        </div>
    </div>
</body>
</html>
