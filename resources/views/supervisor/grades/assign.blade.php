<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assign Final Grade - Supervisor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .sticky-banner {
            position: sticky;
            top: 0;
            z-index: 50;
        }
        .tab-active {
            border-bottom: 3px solid #2563eb;
            color: #2563eb;
            background-color: #eff6ff;
        }
        .tab-inactive {
            color: #6b7280;
            border-bottom: 2px solid transparent;
        }
        .error-input {
            border-color: #ef4444 !important;
            background-color: #fee2e2;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Final Year Project - Supervisor</h1>
            <div class="flex gap-4">
                <a href="{{ route('supervisor.dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100 transition">
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
    <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">
                @if($project->finalGrade)
                    Edit Final Grade
                @else
                    Assign Final Grade
                @endif
            </h2>
            <p class="text-gray-600 mt-2">Provide a comprehensive final grade for this FYP</p>
        </div>

        <!-- Project Details -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Details</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Project Title:</p>
                    <p class="font-medium text-gray-900">{{ $project->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Student:</p>
                    <p class="font-medium text-gray-900">{{ $project->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $project->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Project Type:</p>
                    <p class="font-medium text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $project->user->project ?? 'N/A' }}
                        </span>
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Programme:</p>
                    <p class="font-medium text-gray-900">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            {{ $project->user->programme ?? 'N/A' }}
                        </span>
                    </p>
                </div>
            </div>
            @if($project->description)
                <div class="mt-4">
                    <p class="text-sm text-gray-600">Description:</p>
                    <p class="text-gray-800">{{ $project->description }}</p>
                </div>
            @endif
        </div>

        @if($project->user->project === 'FYP I')
            <!-- FYP I Rubric-Based Grading System -->
            <!-- Sticky Grand Total Banner -->
            <div class="sticky-banner bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-lg shadow-lg p-6 mb-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-2xl font-bold">Grand Total</h3>
                        <p class="text-blue-100 text-sm">Real-time calculation based on rubric</p>
                    </div>
                    <div class="text-right">
                        <div class="text-5xl font-bold" id="grandTotal">0.00</div>
                        <div class="text-blue-100 text-sm">out of 100%</div>
                    </div>
                </div>
                <div class="grid grid-cols-4 gap-4 mt-4">
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <div class="text-xs text-blue-100">Prototype</div>
                        <div class="text-xl font-bold" id="prototypeTotal">0.00%</div>
                        <div class="text-xs text-blue-100">Max: 60%</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <div class="text-xs text-blue-100">Interim Report</div>
                        <div class="text-xl font-bold" id="reportTotal">0.00%</div>
                        <div class="text-xs text-blue-100">Max: 20%</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <div class="text-xs text-blue-100">Presentation</div>
                        <div class="text-xl font-bold" id="presentationTotal">0.00%</div>
                        <div class="text-xs text-blue-100">Max: 10%</div>
                    </div>
                    <div class="bg-white bg-opacity-20 rounded-lg p-3">
                        <div class="text-xs text-blue-100">Attitude</div>
                        <div class="text-xl font-bold" id="attitudeTotal">0.00%</div>
                        <div class="text-xs text-blue-100">Max: 10%</div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Interface -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                <!-- Tab Headers -->
                <div class="flex border-b border-gray-200">
                    <button type="button" onclick="switchTab('prototype')" id="tab-prototype" 
                            class="flex-1 px-6 py-4 text-sm font-medium tab-active transition">
                        Prototype (60%)
                    </button>
                    <button type="button" onclick="switchTab('report')" id="tab-report" 
                            class="flex-1 px-6 py-4 text-sm font-medium tab-inactive transition">
                        Interim Report (20%)
                    </button>
                    <button type="button" onclick="switchTab('presentation')" id="tab-presentation" 
                            class="flex-1 px-6 py-4 text-sm font-medium tab-inactive transition">
                        Presentation (10%)
                    </button>
                    <button type="button" onclick="switchTab('attitude')" id="tab-attitude" 
                            class="flex-1 px-6 py-4 text-sm font-medium tab-inactive transition">
                        Attitude (10%)
                    </button>
                </div>

                <form method="POST" action="{{ route('supervisor.grades.store', $project->id) }}" id="rubricForm">
                    @csrf

                    <!-- Tab Content -->
                    <div class="p-6">
                        <!-- Prototype Tab -->
                        <div id="content-prototype" class="tab-content">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Prototype Assessment (Total: 60%)</h3>
                            
                            <div class="space-y-4">
                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Preliminary Study
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.4% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Assessment of initial research, requirements gathering, and feasibility analysis</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="prototype_preliminary" id="prototype_preliminary" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_prototype_preliminary">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        GUI (Graphical User Interface)
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.6% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Evaluation of interface design, usability, and user experience</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="prototype_gui" id="prototype_gui" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_prototype_gui">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Functions
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 1.0% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Implementation quality, feature completeness, and technical correctness</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="prototype_functions" id="prototype_functions" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_prototype_functions">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Ownership
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.5% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Evidence of independent work, understanding, and contribution</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="prototype_ownership" id="prototype_ownership" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_prototype_ownership">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Innovation
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.5% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Creativity, originality, and novel approaches in the solution</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="prototype_innovation" id="prototype_innovation" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_prototype_innovation">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        System Flow
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 3.0% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Logic flow, architecture design, and system integration</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="prototype_system_flow" id="prototype_system_flow" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_prototype_system_flow">→ Score: 0.00%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Interim Report Tab -->
                        <div id="content-report" class="tab-content hidden">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Interim Report Assessment (Total: 20%)</h3>
                            
                            <div class="space-y-4">
                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Chapter 1 - Introduction
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.32% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Background, problem statement, objectives, and scope</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="report_chapter1" id="report_chapter1" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_report_chapter1">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Chapter 2 - Literature Review
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.32% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Related work, theoretical framework, and research foundation</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="report_chapter2" id="report_chapter2" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_report_chapter2">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Chapter 3 - Methodology
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.32% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Research approach, design methodology, and implementation plan</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="report_chapter3" id="report_chapter3" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_report_chapter3">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Chapter 4 - Results/Analysis
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.32% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Findings presentation, data analysis, and interpretation</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="report_chapter4" id="report_chapter4" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_report_chapter4">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Chapter 5 - Conclusion
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.32% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Summary, contributions, limitations, and future work</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="report_chapter5" id="report_chapter5" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_report_chapter5">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Report Structure
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.4% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Organization, formatting, citations, and overall presentation quality</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="report_structure" id="report_structure" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_report_structure">→ Score: 0.00%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Presentation Tab -->
                        <div id="content-presentation" class="tab-content hidden">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Presentation Assessment (Total: 10%)</h3>
                            
                            <div class="space-y-4">
                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Preparation
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Readiness, organization, and time management during presentation</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="presentation_preparation" id="presentation_preparation" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_presentation_preparation">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Slides Quality
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Visual design, clarity, and effectiveness of presentation materials</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="presentation_slides" id="presentation_slides" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_presentation_slides">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Content Delivery
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.3% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Communication skills, clarity of explanation, and engagement</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="presentation_content" id="presentation_content" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_presentation_content">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Q&A Performance
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.3% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Ability to answer questions, defend decisions, and demonstrate understanding</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="presentation_qa" id="presentation_qa" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_presentation_qa">→ Score: 0.00%</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attitude Tab -->
                        <div id="content-attitude" class="tab-content hidden">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Attitude Assessment (Total: 10%)</h3>
                            
                            <div class="space-y-4">
                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Relationships
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Professional interaction with supervisor, peers, and stakeholders</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="attitude_relationships" id="attitude_relationships" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_attitude_relationships">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Planning & Time Management
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Ability to plan work, meet deadlines, and manage project timeline</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="attitude_planning" id="attitude_planning" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_attitude_planning">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Ethical Conduct
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Academic integrity, honesty, and ethical research practices</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="attitude_ethical" id="attitude_ethical" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_attitude_ethical">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Independent Learning
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Self-motivation, initiative, and ability to work independently</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="attitude_independent" id="attitude_independent" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_attitude_independent">→ Score: 0.00%</span>
                                    </div>
                                </div>

                                <div class="rubric-row">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        Regular Updates & Communication
                                        <span class="text-xs text-gray-500 ml-2">(Weight: 0.2% per point)</span>
                                    </label>
                                    <p class="text-xs text-gray-500 mb-2">Consistent progress reporting and responsive communication</p>
                                    <div class="flex items-center gap-4">
                                        <input type="number" name="attitude_updates" id="attitude_updates" 
                                               min="0" max="10" step="0.1" value="0"
                                               class="w-32 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                               oninput="validateAndCalculate(this)">
                                        <span class="text-sm text-gray-600">/ 10</span>
                                        <span class="text-sm font-medium text-blue-600" id="score_attitude_updates">→ Score: 0.00%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor Remarks -->
                    <div class="border-t border-gray-200 p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Supervisor Remarks</h3>
                        <textarea name="comments" id="comments" rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                  placeholder="Provide detailed feedback and comments about the student's overall performance, strengths, areas for improvement, and justification for the assigned grades...">{{ old('comments', $project->finalGrade->comments ?? '') }}</textarea>
                        <p class="mt-2 text-sm text-gray-500">
                            Provide comprehensive remarks explaining the grades awarded and offering constructive feedback.
                        </p>
                    </div>

                    <!-- Hidden field for final grade -->
                    <input type="hidden" name="final_grade" id="final_grade" value="0">

                    <!-- Form Actions -->
                    <div class="border-t border-gray-200 p-6">
                        <div class="flex gap-4">
                            <button type="submit" id="submitBtn"
                                    class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition font-medium">
                                @if($project->finalGrade)
                                    Update Grade
                                @else
                                    Submit Grade
                                @endif
                            </button>
                            <a href="{{ route('supervisor.grades.index') }}" 
                               class="bg-gray-200 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-300 transition font-medium">
                                Cancel
                            </a>
                        </div>
                        <p class="mt-3 text-sm text-gray-500">
                            <span class="font-medium text-red-600">*</span> The submit button will be disabled if any input exceeds 10 points.
                        </p>
                    </div>
                </form>
            </div>

            <script>
                // FYP I Rubric Definition - Constant Data Object
                const FYP_I_RUBRIC = {
                    prototype: {
                        maxTotal: 60,
                        criteria: {
                            preliminary: { weight: 0.4, max: 10 },
                            gui: { weight: 0.6, max: 10 },
                            functions: { weight: 1.0, max: 10 },
                            ownership: { weight: 0.5, max: 10 },
                            innovation: { weight: 0.5, max: 10 },
                            system_flow: { weight: 3.0, max: 10 }
                        }
                    },
                    report: {
                        maxTotal: 20,
                        criteria: {
                            chapter1: { weight: 0.32, max: 10 },
                            chapter2: { weight: 0.32, max: 10 },
                            chapter3: { weight: 0.32, max: 10 },
                            chapter4: { weight: 0.32, max: 10 },
                            chapter5: { weight: 0.32, max: 10 },
                            structure: { weight: 0.4, max: 10 }
                        }
                    },
                    presentation: {
                        maxTotal: 10,
                        criteria: {
                            preparation: { weight: 0.2, max: 10 },
                            slides: { weight: 0.2, max: 10 },
                            content: { weight: 0.3, max: 10 },
                            qa: { weight: 0.3, max: 10 }
                        }
                    },
                    attitude: {
                        maxTotal: 10,
                        criteria: {
                            relationships: { weight: 0.2, max: 10 },
                            planning: { weight: 0.2, max: 10 },
                            ethical: { weight: 0.2, max: 10 },
                            independent: { weight: 0.2, max: 10 },
                            updates: { weight: 0.2, max: 10 }
                        }
                    }
                };

                // Get current rubric state from form inputs
                function getRubricState() {
                    return {
                        prototype: {
                            preliminary: parseFloat(document.getElementById('prototype_preliminary').value) || 0,
                            gui: parseFloat(document.getElementById('prototype_gui').value) || 0,
                            functions: parseFloat(document.getElementById('prototype_functions').value) || 0,
                            ownership: parseFloat(document.getElementById('prototype_ownership').value) || 0,
                            innovation: parseFloat(document.getElementById('prototype_innovation').value) || 0,
                            system_flow: parseFloat(document.getElementById('prototype_system_flow').value) || 0
                        },
                        report: {
                            chapter1: parseFloat(document.getElementById('report_chapter1').value) || 0,
                            chapter2: parseFloat(document.getElementById('report_chapter2').value) || 0,
                            chapter3: parseFloat(document.getElementById('report_chapter3').value) || 0,
                            chapter4: parseFloat(document.getElementById('report_chapter4').value) || 0,
                            chapter5: parseFloat(document.getElementById('report_chapter5').value) || 0,
                            structure: parseFloat(document.getElementById('report_structure').value) || 0
                        },
                        presentation: {
                            preparation: parseFloat(document.getElementById('presentation_preparation').value) || 0,
                            slides: parseFloat(document.getElementById('presentation_slides').value) || 0,
                            content: parseFloat(document.getElementById('presentation_content').value) || 0,
                            qa: parseFloat(document.getElementById('presentation_qa').value) || 0
                        },
                        attitude: {
                            relationships: parseFloat(document.getElementById('attitude_relationships').value) || 0,
                            planning: parseFloat(document.getElementById('attitude_planning').value) || 0,
                            ethical: parseFloat(document.getElementById('attitude_ethical').value) || 0,
                            independent: parseFloat(document.getElementById('attitude_independent').value) || 0,
                            updates: parseFloat(document.getElementById('attitude_updates').value) || 0
                        }
                    };
                }

                // Calculate Scores Function
                function calculateScores(state) {
                    let prototypeTotal = 0;
                    let reportTotal = 0;
                    let presentationTotal = 0;
                    let attitudeTotal = 0;

                    // Calculate Prototype Total
                    for (const [key, value] of Object.entries(state.prototype)) {
                        const weight = FYP_I_RUBRIC.prototype.criteria[key].weight;
                        prototypeTotal += value * weight;
                    }

                    // Calculate Report Total
                    for (const [key, value] of Object.entries(state.report)) {
                        const weight = FYP_I_RUBRIC.report.criteria[key].weight;
                        reportTotal += value * weight;
                    }

                    // Calculate Presentation Total
                    for (const [key, value] of Object.entries(state.presentation)) {
                        const weight = FYP_I_RUBRIC.presentation.criteria[key].weight;
                        presentationTotal += value * weight;
                    }

                    // Calculate Attitude Total
                    for (const [key, value] of Object.entries(state.attitude)) {
                        const weight = FYP_I_RUBRIC.attitude.criteria[key].weight;
                        attitudeTotal += value * weight;
                    }

                    const grandTotal = prototypeTotal + reportTotal + presentationTotal + attitudeTotal;

                    return {
                        prototypeTotal: prototypeTotal,
                        reportTotal: reportTotal,
                        presentationTotal: presentationTotal,
                        attitudeTotal: attitudeTotal,
                        grandTotal: grandTotal
                    };
                }

                // Validate and Calculate
                function validateAndCalculate(input) {
                    const value = parseFloat(input.value);
                    const submitBtn = document.getElementById('submitBtn');
                    
                    // Validation
                    if (value > 10 || value < 0 || isNaN(value)) {
                        input.classList.add('error-input');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else {
                        input.classList.remove('error-input');
                        
                        // Check if all inputs are valid
                        const allInputs = document.querySelectorAll('input[type="number"]:not(#final_grade)');
                        let allValid = true;
                        allInputs.forEach(inp => {
                            const val = parseFloat(inp.value);
                            if (val > 10 || val < 0 || isNaN(val)) {
                                allValid = false;
                            }
                        });
                        
                        if (allValid) {
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        }
                    }

                    // Update individual score display
                    const fieldId = input.id;
                    const [section, criterion] = fieldId.split('_').slice(0, 2);
                    const criterionKey = fieldId.split('_').slice(1).join('_');
                    
                    let weight = 0;
                    if (FYP_I_RUBRIC[section] && FYP_I_RUBRIC[section].criteria[criterionKey]) {
                        weight = FYP_I_RUBRIC[section].criteria[criterionKey].weight;
                    }
                    
                    const score = value * weight;
                    const scoreElement = document.getElementById('score_' + fieldId);
                    if (scoreElement) {
                        scoreElement.textContent = `→ Score: ${score.toFixed(2)}%`;
                    }

                    // Recalculate totals
                    updateTotals();
                }

                // Update Total Displays
                function updateTotals() {
                    const state = getRubricState();
                    const scores = calculateScores(state);

                    document.getElementById('prototypeTotal').textContent = scores.prototypeTotal.toFixed(2) + '%';
                    document.getElementById('reportTotal').textContent = scores.reportTotal.toFixed(2) + '%';
                    document.getElementById('presentationTotal').textContent = scores.presentationTotal.toFixed(2) + '%';
                    document.getElementById('attitudeTotal').textContent = scores.attitudeTotal.toFixed(2) + '%';
                    document.getElementById('grandTotal').textContent = scores.grandTotal.toFixed(2);

                    // Update hidden field for form submission
                    document.getElementById('final_grade').value = scores.grandTotal.toFixed(2);
                }

                // Tab Switching
                function switchTab(tabName) {
                    // Hide all tab contents
                    document.querySelectorAll('.tab-content').forEach(content => {
                        content.classList.add('hidden');
                    });

                    // Remove active class from all tabs
                    document.querySelectorAll('[id^="tab-"]').forEach(tab => {
                        tab.classList.remove('tab-active');
                        tab.classList.add('tab-inactive');
                    });

                    // Show selected tab content
                    document.getElementById('content-' + tabName).classList.remove('hidden');

                    // Add active class to selected tab
                    document.getElementById('tab-' + tabName).classList.remove('tab-inactive');
                    document.getElementById('tab-' + tabName).classList.add('tab-active');
                }

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    // Initialize all score displays
                    const allInputs = document.querySelectorAll('input[type="number"]:not(#final_grade)');
                    allInputs.forEach(input => {
                        validateAndCalculate(input);
                    });
                    
                    updateTotals();
                });
            </script>

        @else
            <!-- FYP II / Project - Rubric-based grading -->
            @php
                // Determine user role for this project
                $userRole = (auth()->user()->id === $project->supervisor_id) ? 'Supervisor' : 'Examiner';
            @endphp

            <!-- Sticky Grand Total Banner -->
            <div class="sticky-banner">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-bold">Grand Total:</span>
                    <span id="grand-total-display" class="text-2xl font-bold">0.00</span>
                </div>
                <div class="mt-3 grid grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="font-medium">Technical:</span>
                        <span id="technical-total-display" class="font-semibold ml-2">0.00</span>
                    </div>
                    <div>
                        <span class="font-medium">Report:</span>
                        <span id="report-total-display" class="font-semibold ml-2">0.00</span>
                    </div>
                    <div>
                        <span class="font-medium">Presentation:</span>
                        <span id="presentation-total-display" class="font-semibold ml-2">0.00</span>
                    </div>
                </div>
            </div>

            <!-- Grading Rubric Reference -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
            <!-- Grading Rubric Reference -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3">Grading Rubric - {{ $project->user->project }}</h3>
                <div class="grid grid-cols-3 gap-4 text-sm text-blue-900">
                    <div>
                        <strong>Technical Achievements (60%)</strong>
                        <ul class="list-disc list-inside mt-1">
                            <li>Innovation: 10%</li>
                            <li>Functionalities: 20%</li>
                            <li>Quality: 20%</li>
                            <li>Effort: 10%</li>
                        </ul>
                    </div>
                    <div>
                        <strong>Project Report (20%)</strong>
                        <ul class="list-disc list-inside mt-1">
                            <li>Introduction: 10%</li>
                            <li>Analysis & Requirement: 20%</li>
                            <li>Design & Coding: 40%</li>
                            <li>Testing & Debugging: 20%</li>
                            <li>Conclusion: 10%</li>
                        </ul>
                    </div>
                    <div>
                        <strong>Presentation (20%)</strong>
                        <ul class="list-disc list-inside mt-1">
                            <li>Preparation: 20%</li>
                            <li>Slides & Skills: 20%</li>
                            <li>Content: 30%</li>
                            <li>Q&A Session: 30%</li>
                        </ul>
                    </div>
                </div>
                <p class="mt-3 text-sm text-blue-800">
                    <strong>Grading:</strong> Each criterion is scored 0-10. Scores are weighted and calculated automatically.
                </p>
            </div>

            <!-- Tab Navigation -->
            <div class="bg-white rounded-lg shadow-md mb-6">
                <div class="border-b border-gray-200">
                    <nav class="flex">
                        <button type="button" onclick="switchTabFYP2('technical')" class="tab-active px-6 py-3 text-sm font-medium" id="tab-technical">
                            Technical Achievements (60%)
                        </button>
                        <button type="button" onclick="switchTabFYP2('report')" class="tab-inactive px-6 py-3 text-sm font-medium" id="tab-report">
                            Project Report (20%)
                        </button>
                        <button type="button" onclick="switchTabFYP2('presentation')" class="tab-inactive px-6 py-3 text-sm font-medium" id="tab-presentation">
                            Presentation (20%)
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Grading Form -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <form method="POST" action="{{ route('supervisor.grades.store', $project->id) }}" id="grading-form-fyp2">
                    @csrf

                    <!-- Hidden field for final grade -->
                    <input type="hidden" name="final_grade" id="final_grade_fyp2">

                    <!-- Technical Achievements Tab -->
                    <div id="content-technical" class="tab-content">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Technical Achievements (60%)</h3>
                        
                        <!-- Innovation -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Innovation (Weight: 10%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Usefulness (Commercial potential)</li>
                                <li>Creative (Valuable/Different idea)</li>
                                <li>Novelty (New and original)</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="technical_innovation" 
                                        id="technical_innovation"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_technical_innovation"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Functionalities -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Functionalities (Weight: 20%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Main functionalities completeness</li>
                                <li>Additional features</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="technical_functionalities" 
                                        id="technical_functionalities"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_technical_functionalities"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Quality -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Quality (Weight: 20%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Code quality and organization</li>
                                <li>User interface design</li>
                                <li>System performance and reliability</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="technical_quality" 
                                        id="technical_quality"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_technical_quality"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Effort (Role-based) -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Effort (Weight: 10%)
                            </label>
                            @if($userRole === 'Supervisor')
                                <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                    <li>Completion (Based on proposal)</li>
                                    <li>Delivery (Timeliness/Schedule)</li>
                                </ul>
                            @else
                                <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                    <li>Completion (Based on proposal)</li>
                                    <li>Problem-solving Ability (Overcoming obstacles)</li>
                                </ul>
                            @endif
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="technical_effort" 
                                        id="technical_effort"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_technical_effort"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Project Report Tab -->
                    <div id="content-report" class="tab-content" style="display: none;">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Project Report (20%)</h3>
                        
                        <!-- Introduction -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Introduction (Weight: 10%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Problem statement clarity</li>
                                <li>Objectives and scope</li>
                                <li>Background research</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="report_introduction" 
                                        id="report_introduction"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_report_introduction"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Analysis and Requirement -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Analysis and Requirement (Weight: 20%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Requirements gathering and analysis</li>
                                <li>System specifications</li>
                                <li>Use cases and scenarios</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="report_analysis" 
                                        id="report_analysis"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_report_analysis"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Design and Coding -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Design and Coding (Weight: 40%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>System architecture and design</li>
                                <li>Database design</li>
                                <li>Implementation details</li>
                                <li>Code documentation</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="report_design" 
                                        id="report_design"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_report_design"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Testing and Debugging -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Testing and Debugging (Weight: 20%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Testing strategies and methodologies</li>
                                <li>Test cases and results</li>
                                <li>Bug fixes and improvements</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="report_testing" 
                                        id="report_testing"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_report_testing"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Conclusion -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Conclusion (Weight: 10%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Summary of achievements</li>
                                <li>Limitations and challenges</li>
                                <li>Future work and recommendations</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="report_conclusion" 
                                        id="report_conclusion"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_report_conclusion"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Presentation Tab -->
                    <div id="content-presentation" class="tab-content" style="display: none;">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Presentation (20%)</h3>
                        
                        <!-- Preparation -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Preparation (Weight: 20%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Time management</li>
                                <li>Organization and structure</li>
                                <li>Professional demeanor</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="presentation_preparation" 
                                        id="presentation_preparation"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_presentation_preparation"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Slides and Skills -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Slides and Skills (Weight: 20%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Slide quality and design</li>
                                <li>Communication skills</li>
                                <li>Engagement and delivery</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="presentation_slides" 
                                        id="presentation_slides"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_presentation_slides"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="mb-6 pb-6 border-b border-gray-200">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Content (Weight: 30%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Depth of technical understanding</li>
                                <li>Demonstration of project features</li>
                                <li>Clarity of explanation</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="presentation_content" 
                                        id="presentation_content"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_presentation_content"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Q&A Session -->
                        <div class="mb-6">
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Q&A Session (Weight: 30%)
                            </label>
                            <ul class="list-disc list-inside text-sm text-gray-600 mb-3 ml-2">
                                <li>Response quality and accuracy</li>
                                <li>Ability to defend decisions</li>
                                <li>Knowledge demonstration</li>
                            </ul>
                            <div class="flex gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs text-gray-600 mb-1">Score (0-10)</label>
                                    <input 
                                        type="number" 
                                        name="presentation_qa" 
                                        id="presentation_qa"
                                        min="0" 
                                        max="10" 
                                        step="0.1"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg"
                                        oninput="validateAndCalculateFYP2(this)"
                                    >
                                </div>
                                <div class="flex-[2]">
                                    <label class="block text-xs text-gray-600 mb-1">Remarks</label>
                                    <textarea 
                                        name="remarks_presentation_qa"
                                        rows="2"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm"
                                        placeholder="Optional remarks..."
                                    ></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Comments -->
                    <div class="mb-6 pt-6 border-t-2 border-gray-300">
                        <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                            Overall Comments / Justification
                        </label>
                        <textarea 
                            name="comments" 
                            id="comments"
                            rows="6"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Provide comprehensive feedback explaining the grade awarded, highlighting strengths and areas for improvement..."
                        >{{ old('comments', $project->finalGrade->comments ?? '') }}</textarea>
                        @error('comments')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex gap-4">
                        <button 
                            type="submit" 
                            class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition font-medium"
                        >
                            @if($project->finalGrade)
                                Update Grade
                            @else
                                Submit Grade
                            @endif
                        </button>
                        <a 
                            href="{{ route('supervisor.grades.index') }}" 
                            class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-300 transition font-medium"
                        >
                            Cancel
                        </a>
                    </div>
                </form>
            </div>

            <script>
                // FYP II/Project Rubric Configuration
                const FYP_II_PROJECT_RUBRIC = {
                    technical: {
                        weight: 60,
                        criteria: {
                            innovation: { weight: 10, max: 10 },
                            functionalities: { weight: 20, max: 10 },
                            quality: { weight: 20, max: 10 },
                            effort: { weight: 10, max: 10 }
                        }
                    },
                    report: {
                        weight: 20,
                        criteria: {
                            introduction: { weight: 10, max: 10 },
                            analysis: { weight: 20, max: 10 },
                            design: { weight: 40, max: 10 },
                            testing: { weight: 20, max: 10 },
                            conclusion: { weight: 10, max: 10 }
                        }
                    },
                    presentation: {
                        weight: 20,
                        criteria: {
                            preparation: { weight: 20, max: 10 },
                            slides: { weight: 20, max: 10 },
                            content: { weight: 30, max: 10 },
                            qa: { weight: 30, max: 10 }
                        }
                    }
                };

                // Get current rubric state from form inputs
                function getRubricStateFYP2() {
                    const state = {
                        technical: {},
                        report: {},
                        presentation: {}
                    };

                    // Technical section
                    state.technical.innovation = parseFloat(document.getElementById('technical_innovation')?.value || 0);
                    state.technical.functionalities = parseFloat(document.getElementById('technical_functionalities')?.value || 0);
                    state.technical.quality = parseFloat(document.getElementById('technical_quality')?.value || 0);
                    state.technical.effort = parseFloat(document.getElementById('technical_effort')?.value || 0);

                    // Report section
                    state.report.introduction = parseFloat(document.getElementById('report_introduction')?.value || 0);
                    state.report.analysis = parseFloat(document.getElementById('report_analysis')?.value || 0);
                    state.report.design = parseFloat(document.getElementById('report_design')?.value || 0);
                    state.report.testing = parseFloat(document.getElementById('report_testing')?.value || 0);
                    state.report.conclusion = parseFloat(document.getElementById('report_conclusion')?.value || 0);

                    // Presentation section
                    state.presentation.preparation = parseFloat(document.getElementById('presentation_preparation')?.value || 0);
                    state.presentation.slides = parseFloat(document.getElementById('presentation_slides')?.value || 0);
                    state.presentation.content = parseFloat(document.getElementById('presentation_content')?.value || 0);
                    state.presentation.qa = parseFloat(document.getElementById('presentation_qa')?.value || 0);

                    return state;
                }

                // Calculate weighted scores
                function calculateScoresFYP2(state) {
                    const scores = {
                        technical: 0,
                        report: 0,
                        presentation: 0,
                        grandTotal: 0
                    };

                    // Technical: Direct formula (score / 10) × weight
                    for (const [key, criterion] of Object.entries(FYP_II_PROJECT_RUBRIC.technical.criteria)) {
                        const score = state.technical[key] || 0;
                        scores.technical += (score / 10) * criterion.weight;
                    }

                    // Report: Scaled formula ((score / 10) × weight) / 100 × 20
                    let reportRaw = 0;
                    for (const [key, criterion] of Object.entries(FYP_II_PROJECT_RUBRIC.report.criteria)) {
                        const score = state.report[key] || 0;
                        reportRaw += (score / 10) * criterion.weight;
                    }
                    scores.report = (reportRaw / 100) * 20;

                    // Presentation: Scaled formula (same as report)
                    let presentationRaw = 0;
                    for (const [key, criterion] of Object.entries(FYP_II_PROJECT_RUBRIC.presentation.criteria)) {
                        const score = state.presentation[key] || 0;
                        presentationRaw += (score / 10) * criterion.weight;
                    }
                    scores.presentation = (presentationRaw / 100) * 20;

                    // Grand total
                    scores.grandTotal = scores.technical + scores.report + scores.presentation;

                    return scores;
                }

                // Validate input and trigger calculation
                function validateAndCalculateFYP2(input) {
                    const value = parseFloat(input.value);
                    const min = parseFloat(input.min);
                    const max = parseFloat(input.max);

                    if (isNaN(value) || value < min || value > max) {
                        input.classList.add('error-input');
                        return;
                    } else {
                        input.classList.remove('error-input');
                    }

                    updateTotalsFYP2();
                }

                // Update all total displays
                function updateTotalsFYP2() {
                    const state = getRubricStateFYP2();
                    const scores = calculateScoresFYP2(state);

                    // Update displays
                    document.getElementById('technical-total-display').textContent = scores.technical.toFixed(2);
                    document.getElementById('report-total-display').textContent = scores.report.toFixed(2);
                    document.getElementById('presentation-total-display').textContent = scores.presentation.toFixed(2);
                    document.getElementById('grand-total-display').textContent = scores.grandTotal.toFixed(2);

                    // Update hidden field
                    document.getElementById('final_grade_fyp2').value = scores.grandTotal.toFixed(2);
                }

                // Tab switching
                function switchTabFYP2(tabName) {
                    // Hide all tab contents
                    const contents = document.querySelectorAll('.tab-content');
                    contents.forEach(content => content.style.display = 'none');

                    // Remove active class from all tabs
                    const tabs = ['technical', 'report', 'presentation'];
                    tabs.forEach(tab => {
                        document.getElementById(`tab-${tab}`).className = 'tab-inactive px-6 py-3 text-sm font-medium';
                    });

                    // Show selected tab content
                    document.getElementById(`content-${tabName}`).style.display = 'block';
                    document.getElementById(`tab-${tabName}`).className = 'tab-active px-6 py-3 text-sm font-medium';
                }

                // Form submission validation
                document.getElementById('grading-form-fyp2').addEventListener('submit', function(e) {
                    const state = getRubricStateFYP2();
                    const scores = calculateScoresFYP2(state);
                    
                    if (scores.grandTotal === 0) {
                        e.preventDefault();
                        alert('Please enter scores for at least one criterion before submitting.');
                        return false;
                    }

                    // Ensure hidden field is updated
                    document.getElementById('final_grade_fyp2').value = scores.grandTotal.toFixed(2);
                });

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    updateTotalsFYP2();
                });
            </script>
        @endif
    </div>
</body>
</html>
