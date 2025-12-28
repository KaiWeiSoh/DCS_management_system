<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meetings - FYP Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Header -->
        <div class="bg-white shadow">
            <div class="max-w-7xl mx-auto px-4 py-4 sm:px-6 lg:px-8 flex justify-between items-center">
                <h1 class="text-2xl font-bold text-gray-900">Meetings</h1>
                <div class="space-x-4">
                    <a href="{{ route('supervisor.dashboard') }}" class="text-blue-600 hover:text-blue-800">Home</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Create Button -->
            <div class="mb-6">
                <a href="{{ route('supervisor.meetings.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Create Meeting
                </a>
            </div>

            <!-- Meetings List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                @forelse($meetings as $meeting)
                    <div class="border-b border-gray-200 px-6 py-4 hover:bg-gray-50">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-medium text-gray-900">{{ $meeting->purpose }}</h3>
                                <div class="mt-2 space-y-1">
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Student:</span> {{ $meeting->student->name }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Date:</span> {{ $meeting->meeting_date->format('d M Y') }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <span class="font-medium">Time:</span> {{ \Carbon\Carbon::parse($meeting->meeting_time)->format('h:i A') }}
                                    </p>
                                    @if($meeting->description)
                                        <p class="text-sm text-gray-600 mt-2">
                                            <span class="font-medium">Description:</span><br>
                                            {{ $meeting->description }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            <div class="ml-4">
                                @if($meeting->meeting_date->isFuture() || $meeting->meeting_date->isToday())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Upcoming
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        Past
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="px-6 py-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No meetings</h3>
                        <p class="mt-1 text-sm text-gray-500">Get started by creating a new meeting.</p>
                        <div class="mt-6">
                            <a href="{{ route('supervisor.meetings.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Create Meeting
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</body>
</html>
