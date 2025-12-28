<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Meeting - FYP Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Create New Meeting</h1>
                <div class="space-x-4">
                    <a href="{{ route('supervisor.meetings.index') }}" class="text-blue-600 hover:text-blue-800">Back to Meetings</a>
                    <a href="{{ route('supervisor.dashboard') }}" class="text-blue-600 hover:text-blue-800">Home</a>
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
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Meeting Details</h3>
                    
                    @if($errors->any())
                        <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('supervisor.meetings.store') }}">
                        @csrf
                        
                        <!-- Select Student -->
                        <div class="mb-6">
                            <label for="student_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Select Student <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="student_id" 
                                id="student_id" 
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
                                <option value="">-- Select a student --</option>
                                @foreach($students as $id => $name)
                                    <option value="{{ $id }}" {{ old('student_id') == $id ? 'selected' : '' }}>
                                        {{ $name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Meeting Date -->
                        <div class="mb-6">
                            <label for="meeting_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Meeting Date <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="meeting_date" 
                                id="meeting_date" 
                                value="{{ old('meeting_date') }}"
                                min="{{ date('Y-m-d') }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
                        </div>

                        <!-- Meeting Time -->
                        <div class="mb-6">
                            <label for="meeting_time" class="block text-sm font-medium text-gray-700 mb-2">
                                Meeting Time <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="time" 
                                name="meeting_time" 
                                id="meeting_time" 
                                value="{{ old('meeting_time') }}"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
                        </div>

                        <!-- Purpose of Meeting -->
                        <div class="mb-6">
                            <label for="purpose" class="block text-sm font-medium text-gray-700 mb-2">
                                Purpose of Meeting <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="purpose" 
                                id="purpose" 
                                value="{{ old('purpose') }}"
                                placeholder="e.g., Project Progress Review, Proposal Discussion"
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                required
                            >
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
                                placeholder="Add any additional details or agenda items for the meeting..."
                                class="shadow-sm focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md"
                            >{{ old('description') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Include meeting agenda, topics to discuss, or any preparation needed.</p>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('supervisor.meetings.index') }}" class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                Create Meeting
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
