<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $role }}'s Assessment - Committee View</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .sticky-banner {
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
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
        .tab-inactive:hover {
            background-color: #f9fafb;
        }
        .readonly-input {
            background-color: #f3f4f6;
            cursor: not-allowed;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-green-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Final Year Project - Committee</h1>
            <div class="flex gap-4">
                <a href="{{ route('committee.grades.review', $project->id) }}" class="bg-white text-green-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                    ← Back to Review
                </a>
                <a href="{{ route('committee.dashboard') }}" class="bg-white text-green-600 px-4 py-2 rounded hover:bg-gray-100 transition">
                    Home
                </a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="max-w-5xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">{{ $role }}'s Final Grade Assessment</h2>
            <p class="text-gray-600 mt-2">View the completed marking rubric and final grade breakdown</p>
        </div>

        <!-- Project Details Card -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Project Information</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Project Title:</p>
                    <p class="font-medium text-gray-900">{{ $project->title }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Student:</p>
                    <p class="font-medium text-gray-900">{{ $project->user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $project->user->email }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Project Type:</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $project->user->project ?? 'N/A' }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Programme:</p>
                    <p class="font-medium text-gray-900">{{ $project->user->programme ?? 'N/A' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Project Description:</p>
                    <p class="text-gray-800">{{ $project->description }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600 mb-1">Graded By:</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $roleColor }}-100 text-{{ $roleColor }}-800">
                        {{ $graderName }} ({{ $role }})
                    </span>
                </div>
                @if($gradedAt)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Graded On:</p>
                    <p class="font-medium text-gray-900">{{ $gradedAt->format('M d, Y h:i A') }}</p>
                </div>
                @endif
            </div>
        </div>

        @php
            $isFypOne = ($project->user->project ?? '') === 'FYP I';
            $grandTotal = 0;
            
            if ($rubricData && is_array($rubricData)) {
                // Calculate section totals
                $prototypeTotal = 0;
                $reportTotal = 0;
                $presentationTotal = 0;
                $attitudeTotal = 0;
                $technicalTotal = 0;
                
                if ($isFypOne) {
                    // FYP I calculations
                    if (isset($rubricData['prototype'])) {
                        $prototypeTotal += ($rubricData['prototype']['preliminary_study'] ?? 0) * 0.4;
                        $prototypeTotal += ($rubricData['prototype']['gui'] ?? 0) * 0.6;
                        $prototypeTotal += ($rubricData['prototype']['functions'] ?? 0) * 1.0;
                        $prototypeTotal += ($rubricData['prototype']['ownership'] ?? 0) * 0.5;
                        $prototypeTotal += ($rubricData['prototype']['innovation'] ?? 0) * 0.5;
                        $prototypeTotal += ($rubricData['prototype']['system_flow'] ?? 0) * 3.0;
                    }
                    
                    if (isset($rubricData['report'])) {
                        $reportTotal += ($rubricData['report']['chapter1'] ?? 0) * 0.3;
                        $reportTotal += ($rubricData['report']['chapter2'] ?? 0) * 0.3;
                        $reportTotal += ($rubricData['report']['chapter3'] ?? 0) * 0.3;
                        $reportTotal += ($rubricData['report']['chapter4'] ?? 0) * 0.3;
                        $reportTotal += ($rubricData['report']['chapter5'] ?? 0) * 0.3;
                        $reportTotal += ($rubricData['report']['chapter6'] ?? 0) * 0.25;
                        $reportTotal += ($rubricData['report']['chapter7'] ?? 0) * 0.25;
                    }
                    
                    if (isset($rubricData['presentation'])) {
                        $presentationTotal += ($rubricData['presentation']['content'] ?? 0) * 0.4;
                        $presentationTotal += ($rubricData['presentation']['presentation_skill'] ?? 0) * 0.3;
                        $presentationTotal += ($rubricData['presentation']['qa'] ?? 0) * 0.3;
                    }
                    
                    if (isset($rubricData['attitude'])) {
                        $attitudeTotal += ($rubricData['attitude']['initiative'] ?? 0) * 0.3;
                        $attitudeTotal += ($rubricData['attitude']['resourcefulness'] ?? 0) * 0.3;
                        $attitudeTotal += ($rubricData['attitude']['progress'] ?? 0) * 0.2;
                        $attitudeTotal += ($rubricData['attitude']['responsibility'] ?? 0) * 0.2;
                    }
                    
                    $grandTotal = $prototypeTotal + $reportTotal + $presentationTotal + $attitudeTotal;
                } else {
                    // FYP II/Project calculations
                    if (isset($rubricData['technical'])) {
                        $technicalTotal += ($rubricData['technical']['problem_solving'] ?? 0) * 1.5;
                        $technicalTotal += ($rubricData['technical']['implementation'] ?? 0) * 1.5;
                        $technicalTotal += ($rubricData['technical']['innovation'] ?? 0) * 1.5;
                        $technicalTotal += ($rubricData['technical']['quality'] ?? 0) * 1.5;
                    }
                    
                    if (isset($rubricData['report'])) {
                        $reportTotal += ($rubricData['report']['content'] ?? 0) * 1.0;
                        $reportTotal += ($rubricData['report']['analysis'] ?? 0) * 1.0;
                    }
                    
                    if (isset($rubricData['presentation'])) {
                        $presentationTotal += ($rubricData['presentation']['delivery'] ?? 0) * 1.0;
                        $presentationTotal += ($rubricData['presentation']['content_presentation'] ?? 0) * 1.0;
                    }
                    
                    $grandTotal = $technicalTotal + $reportTotal + $presentationTotal;
                }
            }
        @endphp

        <!-- Sticky Banner with Totals -->
        <div class="sticky-banner bg-gradient-to-r from-{{ $roleColor }}-600 to-{{ $roleColor }}-700 text-white rounded-lg shadow-lg p-6 mb-6">
            <div class="grid {{ $isFypOne ? 'grid-cols-5' : 'grid-cols-4' }} gap-4 text-center">
                <div class="bg-white bg-opacity-20 rounded-lg p-4">
                    <p class="text-sm opacity-90 mb-1">Grand Total</p>
                    <p class="text-3xl font-bold">{{ number_format($grandTotal, 2) }}</p>
                    <p class="text-xs opacity-75">out of 100</p>
                </div>
                @if($isFypOne)
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Prototype</p>
                        <p class="text-2xl font-bold">{{ number_format($prototypeTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 60</p>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Report</p>
                        <p class="text-2xl font-bold">{{ number_format($reportTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 20</p>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Presentation</p>
                        <p class="text-2xl font-bold">{{ number_format($presentationTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 10</p>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Attitude</p>
                        <p class="text-2xl font-bold">{{ number_format($attitudeTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 10</p>
                    </div>
                @else
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Technical</p>
                        <p class="text-2xl font-bold">{{ number_format($technicalTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 60</p>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Report</p>
                        <p class="text-2xl font-bold">{{ number_format($reportTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 20</p>
                    </div>
                    <div class="bg-white bg-opacity-10 rounded-lg p-4">
                        <p class="text-sm opacity-90 mb-1">Presentation</p>
                        <p class="text-2xl font-bold">{{ number_format($presentationTotal, 2) }}</p>
                        <p class="text-xs opacity-75">out of 20</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab Navigation -->
        @if($isFypOne)
            <div class="bg-white rounded-t-lg shadow-md flex border-b">
                <button onclick="switchTab('prototype')" id="tab-prototype" class="flex-1 px-6 py-4 font-semibold transition tab-active">
                    Prototype Development
                </button>
                <button onclick="switchTab('report')" id="tab-report" class="flex-1 px-6 py-4 font-semibold transition tab-inactive">
                    Interim Report
                </button>
                <button onclick="switchTab('presentation')" id="tab-presentation" class="flex-1 px-6 py-4 font-semibold transition tab-inactive">
                    Presentation
                </button>
                <button onclick="switchTab('attitude')" id="tab-attitude" class="flex-1 px-6 py-4 font-semibold transition tab-inactive">
                    Attitude & Conduct
                </button>
            </div>
        @else
            <div class="bg-white rounded-t-lg shadow-md flex border-b">
                <button onclick="switchTab('technical')" id="tab-technical" class="flex-1 px-6 py-4 font-semibold transition tab-active">
                    Technical Development
                </button>
                <button onclick="switchTab('report')" id="tab-report" class="flex-1 px-6 py-4 font-semibold transition tab-inactive">
                    Final Report
                </button>
                <button onclick="switchTab('presentation')" id="tab-presentation" class="flex-1 px-6 py-4 font-semibold transition tab-inactive">
                    Presentation
                </button>
            </div>
        @endif

        <!-- Tab Content -->
        <div class="bg-white rounded-b-lg shadow-md p-8">
            @if($isFypOne)
                <!-- Prototype Tab -->
                <div id="content-prototype" class="tab-content">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Prototype Development (60%)</h3>
                    
                    <!-- Preliminary Study -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Preliminary Study</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 0.4% (Score × 0.4)</p>
                                <p class="text-sm text-gray-600">Assessment of initial project groundwork and research preparation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['prototype']['preliminary_study'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['prototype']['preliminary_study'] ?? 0) * 0.4, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- GUI -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Graphical User Interface (GUI)</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 0.6% (Score × 0.6)</p>
                                <p class="text-sm text-gray-600">User interface design, usability, and visual appeal</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['prototype']['gui'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['prototype']['gui'] ?? 0) * 0.6, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Functions -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Functions & Features</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 1.0% (Score × 1.0)</p>
                                <p class="text-sm text-gray-600">Completeness and effectiveness of implemented features</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['prototype']['functions'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['prototype']['functions'] ?? 0) * 1.0, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Ownership -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Ownership & Understanding</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 0.5% (Score × 0.5)</p>
                                <p class="text-sm text-gray-600">Demonstration of personal work and deep understanding</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['prototype']['ownership'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['prototype']['ownership'] ?? 0) * 0.5, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Innovation -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Innovation & Creativity</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 0.5% (Score × 0.5)</p>
                                <p class="text-sm text-gray-600">Novel approaches, creative solutions, and originality</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['prototype']['innovation'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['prototype']['innovation'] ?? 0) * 0.5, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- System Flow -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">System Flow & Architecture</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 3.0% (Score × 3.0)</p>
                                <p class="text-sm text-gray-600">System design, architecture, and logical flow</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['prototype']['system_flow'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['prototype']['system_flow'] ?? 0) * 3.0, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Report Tab -->
                <div id="content-report" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Interim Report (20%)</h3>
                    
                    @php
                        $chapters = [
                            'chapter1' => ['title' => 'Chapter 1: Introduction', 'weight' => 0.3],
                            'chapter2' => ['title' => 'Chapter 2: Literature Review', 'weight' => 0.3],
                            'chapter3' => ['title' => 'Chapter 3: Methodology', 'weight' => 0.3],
                            'chapter4' => ['title' => 'Chapter 4: System Analysis', 'weight' => 0.3],
                            'chapter5' => ['title' => 'Chapter 5: System Design', 'weight' => 0.3],
                            'chapter6' => ['title' => 'Chapter 6: Implementation', 'weight' => 0.25],
                            'chapter7' => ['title' => 'Chapter 7: Conclusion & Future Work', 'weight' => 0.25]
                        ];
                    @endphp

                    @foreach($chapters as $key => $chapter)
                        <div class="mb-6 p-6 bg-green-50 rounded-lg border-2 border-green-200">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <label class="text-lg font-semibold text-gray-800 block mb-1">{{ $chapter['title'] }}</label>
                                    <p class="text-sm text-green-700 font-medium mb-1">Weight: {{ $chapter['weight'] }}% (Score × {{ $chapter['weight'] }})</p>
                                    <p class="text-sm text-gray-600">Content quality, structure, and academic rigor</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-4">
                                <input type="number" value="{{ $rubricData['report'][$key] ?? 0 }}" 
                                       class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                       readonly min="0" max="10" step="0.1">
                                <span class="text-gray-600">out of 10</span>
                                <div class="flex-1"></div>
                                <span class="text-lg font-bold text-green-700">
                                    Score: {{ number_format(($rubricData['report'][$key] ?? 0) * $chapter['weight'], 2) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Presentation Tab -->
                <div id="content-presentation" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Presentation (10%)</h3>
                    
                    <!-- Content -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-2 border-purple-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Content & Organization</label>
                                <p class="text-sm text-purple-700 font-medium mb-1">Weight: 0.4% (Score × 0.4)</p>
                                <p class="text-sm text-gray-600">Clarity, structure, and relevance of presented content</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['presentation']['content'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-purple-700">
                                Score: {{ number_format(($rubricData['presentation']['content'] ?? 0) * 0.4, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Presentation Skill -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-2 border-purple-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Presentation Skills</label>
                                <p class="text-sm text-purple-700 font-medium mb-1">Weight: 0.3% (Score × 0.3)</p>
                                <p class="text-sm text-gray-600">Delivery, confidence, and communication effectiveness</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['presentation']['presentation_skill'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-purple-700">
                                Score: {{ number_format(($rubricData['presentation']['presentation_skill'] ?? 0) * 0.3, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Q&A -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-2 border-purple-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Q&A Session</label>
                                <p class="text-sm text-purple-700 font-medium mb-1">Weight: 0.3% (Score × 0.3)</p>
                                <p class="text-sm text-gray-600">Ability to address questions and defend work</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['presentation']['qa'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-purple-700">
                                Score: {{ number_format(($rubricData['presentation']['qa'] ?? 0) * 0.3, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Attitude Tab -->
                <div id="content-attitude" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Attitude & Conduct (10%)</h3>
                    
                    <!-- Initiative -->
                    <div class="mb-6 p-6 bg-orange-50 rounded-lg border-2 border-orange-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Initiative & Proactiveness</label>
                                <p class="text-sm text-orange-700 font-medium mb-1">Weight: 0.3% (Score × 0.3)</p>
                                <p class="text-sm text-gray-600">Self-motivation and proactive problem-solving</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['attitude']['initiative'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-orange-700">
                                Score: {{ number_format(($rubricData['attitude']['initiative'] ?? 0) * 0.3, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Resourcefulness -->
                    <div class="mb-6 p-6 bg-orange-50 rounded-lg border-2 border-orange-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Resourcefulness</label>
                                <p class="text-sm text-orange-700 font-medium mb-1">Weight: 0.3% (Score × 0.3)</p>
                                <p class="text-sm text-gray-600">Ability to find solutions and utilize resources effectively</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['attitude']['resourcefulness'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-orange-700">
                                Score: {{ number_format(($rubricData['attitude']['resourcefulness'] ?? 0) * 0.3, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Progress -->
                    <div class="mb-6 p-6 bg-orange-50 rounded-lg border-2 border-orange-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Progress & Development</label>
                                <p class="text-sm text-orange-700 font-medium mb-1">Weight: 0.2% (Score × 0.2)</p>
                                <p class="text-sm text-gray-600">Consistent progress and skill development throughout project</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['attitude']['progress'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-orange-700">
                                Score: {{ number_format(($rubricData['attitude']['progress'] ?? 0) * 0.2, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Responsibility -->
                    <div class="mb-6 p-6 bg-orange-50 rounded-lg border-2 border-orange-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Responsibility & Punctuality</label>
                                <p class="text-sm text-orange-700 font-medium mb-1">Weight: 0.2% (Score × 0.2)</p>
                                <p class="text-sm text-gray-600">Meeting deadlines and maintaining accountability</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['attitude']['responsibility'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-orange-700">
                                Score: {{ number_format(($rubricData['attitude']['responsibility'] ?? 0) * 0.2, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

            @else
                <!-- Technical Tab (FYP II/Project) -->
                <div id="content-technical" class="tab-content">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Technical Development (60%)</h3>
                    
                    <!-- Problem Solving -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Problem Solving & Analysis</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 1.5% (Score × 1.5)</p>
                                <p class="text-sm text-gray-600">Approach to identifying and solving technical challenges</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['technical']['problem_solving'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['technical']['problem_solving'] ?? 0) * 1.5, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Implementation -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Implementation Quality</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 1.5% (Score × 1.5)</p>
                                <p class="text-sm text-gray-600">Code quality, architecture, and technical execution</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['technical']['implementation'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['technical']['implementation'] ?? 0) * 1.5, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Innovation -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Innovation & Creativity</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 1.5% (Score × 1.5)</p>
                                <p class="text-sm text-gray-600">Novel approaches and creative solutions to problems</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['technical']['innovation'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['technical']['innovation'] ?? 0) * 1.5, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Quality -->
                    <div class="mb-6 p-6 bg-blue-50 rounded-lg border-2 border-blue-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Overall Quality & Testing</label>
                                <p class="text-sm text-blue-700 font-medium mb-1">Weight: 1.5% (Score × 1.5)</p>
                                <p class="text-sm text-gray-600">System reliability, testing coverage, and polish</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['technical']['quality'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-blue-700">
                                Score: {{ number_format(($rubricData['technical']['quality'] ?? 0) * 1.5, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Report Tab (FYP II/Project) -->
                <div id="content-report" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Final Report (20%)</h3>
                    
                    <!-- Content -->
                    <div class="mb-6 p-6 bg-green-50 rounded-lg border-2 border-green-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Content & Structure</label>
                                <p class="text-sm text-green-700 font-medium mb-1">Weight: 1.0% (Score × 1.0)</p>
                                <p class="text-sm text-gray-600">Organization, completeness, and academic quality of report</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['report']['content'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-green-700">
                                Score: {{ number_format(($rubricData['report']['content'] ?? 0) * 1.0, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Analysis -->
                    <div class="mb-6 p-6 bg-green-50 rounded-lg border-2 border-green-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Analysis & Critical Thinking</label>
                                <p class="text-sm text-green-700 font-medium mb-1">Weight: 1.0% (Score × 1.0)</p>
                                <p class="text-sm text-gray-600">Depth of analysis, insights, and critical evaluation</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['report']['analysis'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-green-700">
                                Score: {{ number_format(($rubricData['report']['analysis'] ?? 0) * 1.0, 2) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Presentation Tab (FYP II/Project) -->
                <div id="content-presentation" class="tab-content hidden">
                    <h3 class="text-2xl font-bold text-gray-800 mb-6">Presentation (20%)</h3>
                    
                    <!-- Delivery -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-2 border-purple-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Delivery & Communication</label>
                                <p class="text-sm text-purple-700 font-medium mb-1">Weight: 1.0% (Score × 1.0)</p>
                                <p class="text-sm text-gray-600">Presentation skills, clarity, and audience engagement</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['presentation']['delivery'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-purple-700">
                                Score: {{ number_format(($rubricData['presentation']['delivery'] ?? 0) * 1.0, 2) }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Presentation -->
                    <div class="mb-6 p-6 bg-purple-50 rounded-lg border-2 border-purple-200">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex-1">
                                <label class="text-lg font-semibold text-gray-800 block mb-1">Content & Organization</label>
                                <p class="text-sm text-purple-700 font-medium mb-1">Weight: 1.0% (Score × 1.0)</p>
                                <p class="text-sm text-gray-600">Structure, relevance, and depth of presented material</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <input type="number" value="{{ $rubricData['presentation']['content_presentation'] ?? 0 }}" 
                                   class="w-24 px-3 py-2 border border-gray-300 rounded readonly-input text-center font-semibold" 
                                   readonly min="0" max="10" step="0.1">
                            <span class="text-gray-600">out of 10</span>
                            <div class="flex-1"></div>
                            <span class="text-lg font-bold text-purple-700">
                                Score: {{ number_format(($rubricData['presentation']['content_presentation'] ?? 0) * 1.0, 2) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Comments Section -->
        @if($comments)
        <div class="bg-white rounded-lg shadow-md p-6 mt-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-{{ $roleColor }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                </svg>
                {{ $role }}'s Comments
            </h3>
            <div class="bg-gray-50 border-2 border-gray-200 rounded-lg p-4">
                <p class="text-gray-800 whitespace-pre-wrap">{{ $comments }}</p>
            </div>
        </div>
        @endif
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tab contents
            const contents = document.querySelectorAll('.tab-content');
            contents.forEach(content => content.classList.add('hidden'));
            
            // Remove active class from all tabs
            const tabs = document.querySelectorAll('[id^="tab-"]');
            tabs.forEach(tab => {
                tab.classList.remove('tab-active');
                tab.classList.add('tab-inactive');
            });
            
            // Show selected tab content
            document.getElementById('content-' + tabName).classList.remove('hidden');
            
            // Add active class to selected tab
            document.getElementById('tab-' + tabName).classList.remove('tab-inactive');
            document.getElementById('tab-' + tabName).classList.add('tab-active');
        }
    </script>
</body>
</html>
