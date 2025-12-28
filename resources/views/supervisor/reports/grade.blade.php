<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grade Report - FYP Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Grade Report</h1>
                <div class="space-x-4">
                    <a href="{{ route('supervisor.reports.index') }}" class="text-blue-600 hover:text-blue-800">Back to Reports</a>
                    <a href="{{ route('supervisor.dashboard') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-4xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <!-- Report Details -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Report Details</h3>
                </div>
                <div class="border-t border-gray-200">
                    <dl>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Title</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->title }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Student</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->project->user->name }}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Project</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">{{ $report->project->title }}</dd>
                        </div>
                        <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Submitted Date</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                {{ $report->submitted_at ? $report->submitted_at->format('d M Y H:i') : '-' }}
                            </dd>
                        </div>
                        @if($report->file_path)
                        <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Submitted File</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                                <a href="{{ asset('storage/' . $report->file_path) }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                    View File
                                </a>
                            </dd>
                        </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Grading Form -->
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Grade This Report</h3>
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('supervisor.reports.storeGrade', $report->id) }}">
                        @csrf
                        
                        <!-- Marks Input -->
                        <div class="mb-6">
                            <label for="marks" class="block text-sm font-medium text-gray-700 mb-2">
                                Marks (0-100)
                            </label>
                            <input 
                                type="number" 
                                name="marks" 
                                id="marks" 
                                step="0.01" 
                                min="0" 
                                max="100"
                                value="{{ old('marks', $report->marks) }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
                        </div>

                        <!-- Feedback Textarea with Rubric Guidance -->
                        <div class="mb-6">
                            <label for="feedback" class="block text-sm font-medium text-gray-700 mb-2">
                                Feedback & Comments
                            </label>
                            <p class="text-xs text-gray-500 mb-2">
                                Provide detailed feedback based on the marking rubric criteria: content quality, presentation, methodology, and analysis.
                            </p>
                            <textarea 
                                name="feedback" 
                                id="feedback" 
                                rows="8"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                placeholder="Enter your feedback here..."
                            >{{ old('feedback', $report->feedback) }}</textarea>
                        </div>

                        <!-- Marking Rubric Reference -->
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2">Marking Rubric Reference:</h4>
                            <ul class="text-xs text-blue-800 space-y-1">
                                <li>• <strong>Content & Research (40%):</strong> Depth of research, relevance, and accuracy</li>
                                <li>• <strong>Methodology (25%):</strong> Appropriateness and rigor of approach</li>
                                <li>• <strong>Analysis & Results (25%):</strong> Quality of analysis and interpretation</li>
                                <li>• <strong>Presentation (10%):</strong> Structure, clarity, and formatting</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('supervisor.reports.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Save Grade
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
