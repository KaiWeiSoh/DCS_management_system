<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $role }}'s Assessment - Committee View</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .assessment-table {
            width: 100%;
            border-collapse: collapse;
        }
        .assessment-table th {
            background-color: #f3f4f6;
            padding: 12px;
            text-align: left;
            font-weight: 600;
            border: 1px solid #d1d5db;
        }
        .assessment-table td {
            padding: 10px 12px;
            border: 1px solid #e5e7eb;
        }
        .assessment-table tr:hover {
            background-color: #f9fafb;
        }
        .section-header {
            background-color: #dbeafe;
            font-weight: 700;
            font-size: 1.1rem;
        }
        .total-row {
            background-color: #fef3c7;
            font-weight: 700;
        }
        .grand-total-row {
            background-color: #d1fae5;
            font-weight: 700;
            font-size: 1.2rem;
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
    <div class="max-w-7xl mx-auto px-6 py-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">{{ $role }}'s Final Grade Assessment</h2>
            <p class="text-gray-600 mt-2">Detailed breakdown of marks awarded for FYP I project</p>
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
            // Utility function to calculate weighted score
            function calculateWeightedScore($rawMark, $weight) {
                return number_format($rawMark * $weight, 2);
            }
            
            $isFypOne = ($project->user->project ?? '') === 'FYP I';
            
            // Section totals
            $prototypeTotal = 0;
            $reportTotal = 0;
            $presentationTotal = 0;
            $attitudeTotal = 0;
            $grandTotal = 0;
        @endphp

        @if($isFypOne && $rubricData && is_array($rubricData))
            <!-- PROTOTYPE SECTION (60%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-blue-800 mb-4">PROTOTYPE DEVELOPMENT (60%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $prototypeItems = [
                                    ['key' => 'preliminary_study', 'label' => 'Preliminary Study', 'weight' => 0.4],
                                    ['key' => 'gui', 'label' => 'GUI', 'weight' => 0.6],
                                    ['key' => 'functions', 'label' => 'Functions', 'weight' => 1.0],
                                    ['key' => 'ownership', 'label' => 'Ownership', 'weight' => 0.5],
                                    ['key' => 'innovation', 'label' => 'Innovation', 'weight' => 0.5],
                                    ['key' => 'system_flow', 'label' => 'System Flow', 'weight' => 3.0],
                                ];
                            @endphp
                            
                            @foreach($prototypeItems as $item)
                                @php
                                    $rawMark = $rubricData['prototype'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $prototypeTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">× {{ $item['weight'] }}</td>
                                    <td class="text-center font-semibold text-blue-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">PROTOTYPE TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($prototypeTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- INTERIM REPORT SECTION (20%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-green-800 mb-4">INTERIM REPORT (20%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $reportItems = [
                                    ['key' => 'chapter1', 'label' => 'Ch1: Introduction', 'weight' => 0.32],
                                    ['key' => 'chapter2', 'label' => 'Ch2: Literature Review', 'weight' => 0.32],
                                    ['key' => 'chapter3', 'label' => 'Ch3: Methodology', 'weight' => 0.32],
                                    ['key' => 'chapter4', 'label' => 'Ch4: Results/Analysis', 'weight' => 0.32],
                                    ['key' => 'chapter5', 'label' => 'Ch5: Conclusion', 'weight' => 0.32],
                                    ['key' => 'structure', 'label' => 'Structure & References', 'weight' => 0.4],
                                ];
                            @endphp
                            
                            @foreach($reportItems as $item)
                                @php
                                    $rawMark = $rubricData['report'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $reportTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">× {{ $item['weight'] }}</td>
                                    <td class="text-center font-semibold text-green-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">REPORT TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($reportTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PRESENTATION SECTION (10%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-purple-800 mb-4">PRESENTATION (10%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $presentationItems = [
                                    ['key' => 'preparation', 'label' => 'Preparation', 'weight' => 0.2],
                                    ['key' => 'slides', 'label' => 'Slides', 'weight' => 0.2],
                                    ['key' => 'content', 'label' => 'Content', 'weight' => 0.3],
                                    ['key' => 'qa', 'label' => 'Q&A', 'weight' => 0.3],
                                ];
                            @endphp
                            
                            @foreach($presentationItems as $item)
                                @php
                                    $rawMark = $rubricData['presentation'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $presentationTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">× {{ $item['weight'] }}</td>
                                    <td class="text-center font-semibold text-purple-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">PRESENTATION TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($presentationTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ATTITUDE SECTION (10%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-orange-800 mb-4">ATTITUDE (10%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $attitudeItems = [
                                    ['key' => 'relationships', 'label' => 'Relationships', 'weight' => 0.2],
                                    ['key' => 'planning', 'label' => 'Planning', 'weight' => 0.2],
                                    ['key' => 'ethical', 'label' => 'Ethical', 'weight' => 0.2],
                                    ['key' => 'independent', 'label' => 'Independent', 'weight' => 0.2],
                                    ['key' => 'updates', 'label' => 'Updates', 'weight' => 0.2],
                                ];
                            @endphp
                            
                            @foreach($attitudeItems as $item)
                                @php
                                    $rawMark = $rubricData['attitude'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $attitudeTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">× {{ $item['weight'] }}</td>
                                    <td class="text-center font-semibold text-orange-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">ATTITUDE TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($attitudeTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- GRAND TOTAL -->
            @php
                $grandTotal = $prototypeTotal + $reportTotal + $presentationTotal + $attitudeTotal;
            @endphp
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <tbody>
                            <tr class="grand-total-row">
                                <td colspan="3" class="text-right text-xl">GRAND TOTAL (out of 100%):</td>
                                <td class="text-center text-2xl text-green-700">{{ number_format($grandTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @php
                    $finalGrade = $grandTotal;
                    if($finalGrade >= 80) {
                        $classification = 'First Class';
                        $color = 'text-green-700 bg-green-100';
                    } elseif($finalGrade >= 65) {
                        $classification = 'Upper Second Class';
                        $color = 'text-blue-700 bg-blue-100';
                    } elseif($finalGrade >= 50) {
                        $classification = 'Lower Second Class';
                        $color = 'text-yellow-700 bg-yellow-100';
                    } elseif($finalGrade >= 40) {
                        $classification = 'Third Class';
                        $color = 'text-orange-700 bg-orange-100';
                    } else {
                        $classification = 'Fail';
                        $color = 'text-red-700 bg-red-100';
                    }
                @endphp
                
                <div class="mt-4 text-center">
                    <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold {{ $color }}">
                        {{ $classification }}
                    </span>
                </div>
            </div>

        @else
            <!-- FYP II / PROJECT SECTION -->
            @php
                $technicalTotal = 0;
                $reportTotal = 0;
                $presentationTotal = 0;
            @endphp

            <!-- TECHNICAL ACHIEVEMENTS SECTION (60%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-blue-800 mb-4">TECHNICAL ACHIEVEMENTS (60%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor (%)</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $technicalItems = [
                                    ['key' => 'innovation', 'label' => 'Innovation', 'weight' => 1.0, 'percentage' => '10%'],
                                    ['key' => 'functionalities', 'label' => 'Functionalities', 'weight' => 2.0, 'percentage' => '20%'],
                                    ['key' => 'quality', 'label' => 'Quality', 'weight' => 2.0, 'percentage' => '20%'],
                                    ['key' => 'effort', 'label' => 'Effort', 'weight' => 1.0, 'percentage' => '10%'],
                                ];
                            @endphp
                            
                            @foreach($technicalItems as $item)
                                @php
                                    $rawMark = $rubricData['technical'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $technicalTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">{{ $item['percentage'] }}</td>
                                    <td class="text-center font-semibold text-blue-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">TECHNICAL TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($technicalTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PROJECT REPORT SECTION (20%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-green-800 mb-4">PROJECT REPORT (20%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor (%)</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $reportItems = [
                                    ['key' => 'introduction', 'label' => 'Introduction', 'weight' => 0.2, 'percentage' => '10%'],
                                    ['key' => 'analysis', 'label' => 'Analysis and Requirement', 'weight' => 0.4, 'percentage' => '20%'],
                                    ['key' => 'design', 'label' => 'Design and Coding', 'weight' => 0.8, 'percentage' => '40%'],
                                    ['key' => 'testing', 'label' => 'Testing and Debugging', 'weight' => 0.4, 'percentage' => '20%'],
                                    ['key' => 'conclusion', 'label' => 'Conclusion', 'weight' => 0.2, 'percentage' => '10%'],
                                ];
                            @endphp
                            
                            @foreach($reportItems as $item)
                                @php
                                    $rawMark = $rubricData['report'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $reportTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">{{ $item['percentage'] }}</td>
                                    <td class="text-center font-semibold text-green-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">REPORT TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($reportTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- PRESENTATION SECTION (20%) -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-purple-800 mb-4">PRESENTATION (20%)</h3>
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <thead>
                            <tr>
                                <th style="width: 40%">Criteria</th>
                                <th style="width: 20%" class="text-center">Raw Mark (0-10)</th>
                                <th style="width: 20%" class="text-center">Weight Factor (%)</th>
                                <th style="width: 20%" class="text-center">Final Score (%)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $presentationItems = [
                                    ['key' => 'preparation', 'label' => 'Preparation', 'weight' => 0.4, 'percentage' => '20%'],
                                    ['key' => 'slides', 'label' => 'Slides and Skills', 'weight' => 0.4, 'percentage' => '20%'],
                                    ['key' => 'content', 'label' => 'Content', 'weight' => 0.6, 'percentage' => '30%'],
                                    ['key' => 'qa', 'label' => 'Q&A Session', 'weight' => 0.6, 'percentage' => '30%'],
                                ];
                            @endphp
                            
                            @foreach($presentationItems as $item)
                                @php
                                    $rawMark = $rubricData['presentation'][$item['key']] ?? 0;
                                    $score = $rawMark * $item['weight'];
                                    $presentationTotal += $score;
                                @endphp
                                <tr>
                                    <td class="font-medium">{{ $item['label'] }}</td>
                                    <td class="text-center">{{ number_format($rawMark, 1) }}</td>
                                    <td class="text-center">{{ $item['percentage'] }}</td>
                                    <td class="text-center font-semibold text-purple-700">{{ number_format($score, 2) }}%</td>
                                </tr>
                            @endforeach
                            
                            <tr class="total-row">
                                <td colspan="3" class="text-right">PRESENTATION TOTAL:</td>
                                <td class="text-center text-lg">{{ number_format($presentationTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- GRAND TOTAL -->
            @php
                $grandTotal = $technicalTotal + $reportTotal + $presentationTotal;
            @endphp
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6">
                <div class="overflow-x-auto">
                    <table class="assessment-table">
                        <tbody>
                            <tr class="grand-total-row">
                                <td colspan="3" class="text-right text-xl">GRAND TOTAL (out of 100%):</td>
                                <td class="text-center text-2xl text-green-700">{{ number_format($grandTotal, 2) }}%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                
                @php
                    $finalGrade = $grandTotal;
                    if($finalGrade >= 80) {
                        $classification = 'First Class';
                        $color = 'text-green-700 bg-green-100';
                    } elseif($finalGrade >= 65) {
                        $classification = 'Upper Second Class';
                        $color = 'text-blue-700 bg-blue-100';
                    } elseif($finalGrade >= 50) {
                        $classification = 'Lower Second Class';
                        $color = 'text-yellow-700 bg-yellow-100';
                    } elseif($finalGrade >= 40) {
                        $classification = 'Third Class';
                        $color = 'text-orange-700 bg-orange-100';
                    } else {
                        $classification = 'Fail';
                        $color = 'text-red-700 bg-red-100';
                    }
                @endphp
                
                <div class="mt-4 text-center">
                    <span class="inline-flex items-center px-6 py-3 rounded-full text-lg font-bold {{ $color }}">
                        {{ $classification }}
                    </span>
                </div>
            </div>
        @endif

        @if(!$rubricData || !is_array($rubricData))
            <div class="bg-white rounded-lg shadow-md p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-gray-600 text-lg">No rubric data available</p>
            </div>
        @endif

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
</body>
</html>
