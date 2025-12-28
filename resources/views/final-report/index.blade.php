<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Final Report Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav class="bg-blue-600 text-white px-6 py-4 shadow-md">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <h1 class="text-2xl font-bold">Final Year Project - Student</h1>
            <div class="flex gap-4">
                <a href="{{ route('dashboard') }}" class="bg-white text-blue-600 px-4 py-2 rounded hover:bg-gray-100 transition">
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
    <div class="max-w-4xl mx-auto px-6 py-8">
        <div class="mb-6">
            <h2 class="text-3xl font-bold text-gray-800">Final Report Submission</h2>
            <p class="text-gray-600 mt-2">
                Submit your final year project materials
                @if($user->project)
                    <span class="text-blue-600 font-semibold">(Project: {{ $user->project }}@if($user->programme), Programme: {{ $user->programme }}@endif)</span>
                @endif
            </p>
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

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Current Submission Status -->
        @if($submission)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-xl font-semibold text-gray-800 mb-4">Current Submission</h3>
                
                <div class="space-y-3">
                    @if($project && $project->title)
                        <div class="border border-gray-200 rounded-lg p-3">
                            <span class="font-medium text-gray-700">Project Title:</span>
                            <p class="text-gray-900 mt-1">{{ $project->title }}</p>
                        </div>
                    @endif

                    @if($submission->source_code)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-700">System Source Code</span>
                                <p class="text-sm text-gray-500">{{ basename($submission->source_code) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $submission->source_code) }}" 
                               download
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download
                            </a>
                        </div>
                    @endif

                    @if($submission->abstract)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-700">Abstract</span>
                                <p class="text-sm text-gray-500">{{ basename($submission->abstract) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $submission->abstract) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </div>
                    @endif

                    @if($submission->extended_abstract)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-700">Extended Abstract</span>
                                <p class="text-sm text-gray-500">{{ basename($submission->extended_abstract) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $submission->extended_abstract) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </div>
                    @endif

                    @if($submission->report)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-700">Project Report</span>
                                <p class="text-sm text-gray-500">{{ basename($submission->report) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $submission->report) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                        </div>
                    @endif

                    @if($submission->presentation_video)
                        <div class="border border-gray-200 rounded-lg p-3 flex items-center justify-between">
                            <div>
                                <span class="font-medium text-gray-700">Presentation Video</span>
                                <p class="text-sm text-gray-500">{{ basename($submission->presentation_video) }}</p>
                            </div>
                            <a href="{{ asset('storage/' . $submission->presentation_video) }}" 
                               target="_blank"
                               class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                View
                            </a>
                        </div>
                    @endif

                    <p class="text-sm text-gray-500 mt-3">
                        Submitted on {{ $submission->submitted_at->format('F j, Y, g:i a') }}
                    </p>
                </div>

                <!-- Supervisor Feedback Section -->
                @if($submission->feedback)
                    <div class="mt-4 p-4 bg-blue-50 border-l-4 border-blue-500 rounded">
                        <div class="flex items-start">
                            <svg class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                            </svg>
                            <div class="flex-1">
                                <h4 class="text-lg font-semibold text-blue-900 mb-2">Supervisor's Feedback</h4>
                                <div class="text-sm text-gray-800 whitespace-pre-wrap bg-white p-3 rounded border border-blue-200">{{ $submission->feedback }}</div>
                            </div>
                        </div>
                    </div>
                @endif
                
                <div class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded">
                    <p class="text-sm text-yellow-800">
                        <strong>Note:</strong> Submitting new files will replace your current submission.
                    </p>
                </div>
            </div>
        @endif

        <!-- Submission Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4">
                {{ $submission ? 'Update Project Materials' : 'Submit Project Materials' }}
            </h3>
            
            <form method="POST" action="{{ route('final-report.store') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                @if($user->project !== 'FYP I')
                <!-- Project Title -->
                <div>
                    <label for="project_title" class="block text-sm font-medium text-gray-700 mb-2">
                        Project Title <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           id="project_title" 
                           name="project_title" 
                           value="{{ old('project_title', $project ? $project->title : '') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                           placeholder="Enter your project title"
                           required>
                </div>

                <!-- Abstract -->
                <div>
                    <label for="abstract" class="block text-sm font-medium text-gray-700 mb-2">
                        Abstract <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="abstract" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload abstract</span>
                                    <input id="abstract" name="abstract" type="file" class="sr-only" accept=".pdf,.doc,.docx" {{ $submission && $submission->abstract ? '' : 'required' }}>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 5MB</p>
                            @if($submission && $submission->abstract)
                                <p class="text-xs text-green-600 font-medium">✓ Current: {{ basename($submission->abstract) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Extended Abstract -->
                <div>
                    <label for="extended_abstract" class="block text-sm font-medium text-gray-700 mb-2">
                        Extended Abstract <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="extended_abstract" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload extended abstract</span>
                                    <input id="extended_abstract" name="extended_abstract" type="file" class="sr-only" accept=".pdf,.doc,.docx" {{ $submission && $submission->extended_abstract ? '' : 'required' }}>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 10MB</p>
                            @if($submission && $submission->extended_abstract)
                                <p class="text-xs text-green-600 font-medium">✓ Current: {{ basename($submission->extended_abstract) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- System Source Code -->
                <div>
                    <label for="source_code" class="block text-sm font-medium text-gray-700 mb-2">
                        System Source Code <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="source_code" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload source code</span>
                                    <input id="source_code" name="source_code" type="file" class="sr-only" accept=".zip,.rar" {{ $submission && $submission->source_code ? '' : 'required' }}>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">ZIP or RAR up to 50MB</p>
                            @if($submission && $submission->source_code)
                                <p class="text-xs text-green-600 font-medium">✓ Current: {{ basename($submission->source_code) }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <!-- Project Report -->
                <div>
                    <label for="report" class="block text-sm font-medium text-gray-700 mb-2">
                        Project Report <span class="text-red-500">*</span>
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="report" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                    <span>Upload report</span>
                                    <input id="report" name="report" type="file" class="sr-only" accept=".pdf,.doc,.docx" {{ $submission && $submission->report ? '' : 'required' }}>
                                </label>
                                <p class="pl-1">or drag and drop</p>
                            </div>
                            <p class="text-xs text-gray-500">PDF, DOC, DOCX up to 20MB</p>
                            @if($submission && $submission->report)
                                <p class="text-xs text-green-600 font-medium">✓ Current: {{ basename($submission->report) }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Presentation Video (Only for FYP II and Project students) -->
                @if($user->project !== 'FYP I')
                    <div>
                        <label for="presentation_video" class="block text-sm font-medium text-gray-700 mb-2">
                            Presentation Video <span class="text-red-500">*</span>
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-gray-400 transition">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="presentation_video" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500">
                                        <span>Upload video</span>
                                        <input id="presentation_video" name="presentation_video" type="file" class="sr-only" accept=".mp4,.avi,.mov,.wmv" {{ $submission && $submission->presentation_video ? '' : 'required' }}>
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">MP4, AVI, MOV, WMV up to 100MB</p>
                                @if($submission && $submission->presentation_video)
                                    <p class="text-xs text-green-600 font-medium">✓ Current: {{ basename($submission->presentation_video) }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <div class="flex justify-end space-x-3 pt-4">
                    <a href="{{ route('dashboard') }}" 
                       class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium">
                        {{ $submission ? 'Update Submission' : 'Submit Materials' }}
                    </button>
                </div>
            </form>
        </div>

        <!-- Information Box -->
        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
            <h4 class="font-semibold text-blue-900 mb-2">Submission Guidelines</h4>
            <ul class="list-disc list-inside text-sm text-blue-800 space-y-1">
                @if($user->project === 'FYP I')
                    <li><strong>FYP I:</strong> Only project report is required</li>
                    <li><strong>Report:</strong> Submit in PDF, DOC, or DOCX format (max 20MB)</li>
                @else
                    <li><strong>Project Title:</strong> Enter your complete project title</li>
                    <li><strong>Abstract:</strong> Submit in PDF, DOC, or DOCX format (max 5MB)</li>
                    <li><strong>Extended Abstract:</strong> Submit in PDF, DOC, or DOCX format (max 10MB)</li>
                    <li><strong>Source Code:</strong> Compress all source files into ZIP or RAR (max 50MB)</li>
                    <li><strong>Report:</strong> Submit in PDF, DOC, or DOCX format (max 20MB)</li>
                    <li><strong>Presentation Video:</strong> Required for FYP II and Project students (max 100MB)</li>
                    <li>Accepted video formats: MP4, AVI, MOV, WMV</li>
                @endif
                <li>You can resubmit if needed - the latest submission will be considered</li>
                <li>Your supervisor will be notified upon submission</li>
            </ul>
        </div>
    </div>

    <script>
        // File input preview for abstract (if exists)
        const abstractInput = document.getElementById('abstract');
        if (abstractInput) {
            abstractInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    const label = document.querySelector('label[for="abstract"] span');
                    label.textContent = fileName;
                }
            });
        }

        // File input preview for extended abstract (if exists)
        const extendedAbstractInput = document.getElementById('extended_abstract');
        if (extendedAbstractInput) {
            extendedAbstractInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    const label = document.querySelector('label[for="extended_abstract"] span');
                    label.textContent = fileName;
                }
            });
        }

        // File input preview for source code (if exists)
        const sourceCodeInput = document.getElementById('source_code');
        if (sourceCodeInput) {
            sourceCodeInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    const label = document.querySelector('label[for="source_code"] span');
                    label.textContent = fileName;
                }
            });
        }

        // File input preview for report
        document.getElementById('report').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = document.querySelector('label[for="report"] span');
                label.textContent = fileName;
            }
        });

        // File input preview for video (if exists)
        const videoInput = document.getElementById('presentation_video');
        if (videoInput) {
            videoInput.addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                if (fileName) {
                    const label = document.querySelector('label[for="presentation_video"] span');
                    label.textContent = fileName;
                }
            });
        }
    </script>
</body>
</html>
