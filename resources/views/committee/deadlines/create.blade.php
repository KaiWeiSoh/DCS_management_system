<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Deadline - FYP Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Create New Deadline</h1>
                <div class="space-x-4">
                    <a href="{{ route('committee.deadlines.index') }}" class="text-blue-600 hover:text-blue-800">Back to Deadlines</a>
                    <a href="{{ route('committee.dashboard') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-3xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            <div class="bg-white shadow sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Deadline Details</h3>
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('committee.deadlines.store') }}">
                        @csrf
                        
                        <!-- Subject Title -->
                        <div class="mb-6">
                            <label for="subject_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Subject Title <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="subject_title" 
                                id="subject_title" 
                                value="{{ old('subject_title') }}"
                                placeholder="e.g., FYP Proposal Submission, Final Report Submission"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500">Enter the FYP subject or milestone title.</p>
                        </div>

                        <!-- Deadline Date -->
                        <div class="mb-6">
                            <label for="deadline_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Deadline Date <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="deadline_date" 
                                id="deadline_date" 
                                value="{{ old('deadline_date') }}"
                                min="{{ date('Y-m-d') }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
                            <p class="mt-1 text-xs text-gray-500">Select the deadline date for this subject.</p>
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Description (Optional)
                            </label>
                            <textarea 
                                name="description" 
                                id="description" 
                                rows="4"
                                placeholder="Add any additional details about the deadline, requirements, or submission guidelines..."
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            >{{ old('description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Include submission requirements, format guidelines, or other important information.</p>
                        </div>

                        <!-- Information Box -->
                        <div class="mb-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                            <h4 class="text-sm font-semibold text-blue-900 mb-2">Important Notes:</h4>
                            <ul class="text-xs text-blue-800 space-y-1 list-disc list-inside">
                                <li>All students and supervisors will be able to view this deadline</li>
                                <li>Ensure the deadline date is realistic and allows sufficient time for completion</li>
                                <li>Consider including submission format and requirements in the description</li>
                            </ul>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('committee.deadlines.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Create Deadline
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
