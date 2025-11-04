<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Committee Dashboard</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto p-6">
      <div class="bg-white rounded shadow-lg overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b">
          <h1 class="text-2xl font-semibold">Committee Dashboard</h1>
          <div class="flex items-center space-x-4">
            <a href="{{ route('profile.show') }}" class="text-sm text-gray-700">Profile</a>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="bg-red-600 text-white px-3 py-2 rounded">Log out</button>
            </form>
          </div>
        </div>

        <div class="flex">
          <!-- Sidebar -->
          <aside class="w-64 bg-gray-50 border-r">
            <nav class="p-6 space-y-2">
              <h2 class="text-gray-500 uppercase tracking-wide text-xs mb-2">Sections</h2>
              <a href="{{ route('committee.proposals.index') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-gray-100">Submitted FYP Proposal</a>
              <a href="{{ route('committee.approvals.index') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-gray-100">Project Approval</a>
              <a href="{{ route('committee.grades.index') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-gray-100">Finalize Grade</a>
              <a href="{{ route('committee.deadlines.index') ?? '#' }}" class="block px-3 py-2 rounded hover:bg-gray-100">Deadline</a>
            </nav>
          </aside>

          <!-- Main content -->
          <main class="flex-1 p-6 space-y-6">
            <!-- Submitted FYP Proposals -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Submitted FYP Proposals</h3>
                <a href="{{ route('committee.proposals.index') ?? '#' }}" class="text-sm text-blue-600">View all</a>
              </div>
              <div class="mt-3 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border rounded p-3">
                  <div class="font-semibold">Proposal: Smart Campus</div>
                  <div class="text-sm text-gray-600">Student: Alice Tan</div>
                  <div class="text-sm text-gray-500 mt-2">Submitted: 2025-10-20</div>
                </div>
              </div>
            </section>

            <!-- Project Approval -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Project Approval</h3>
                <a href="{{ route('committee.approvals.index') ?? '#' }}" class="text-sm text-blue-600">Manage approvals</a>
              </div>
              <div class="mt-3">
                <div class="border rounded p-3">No pending approvals.</div>
              </div>
            </section>

            <!-- Finalize Grade -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">Finalize Grade</h3>
                <a href="{{ route('committee.grades.index') ?? '#' }}" class="text-sm text-blue-600">All grades</a>
              </div>
              <div class="mt-3">
                <div class="border rounded p-3">No grades ready to finalize.</div>
              </div>
            </section>

            <!-- Deadline -->
            <section class="bg-white rounded shadow p-4">
              <div class="flex items-center justify-between">
                <h3 class="text-lg font-medium">FYP Deadlines</h3>
                <a href="{{ route('committee.deadlines.index') ?? '#' }}" class="text-sm text-blue-600">Manage deadlines</a>
              </div>
              <div class="mt-3">
                <div class="border rounded p-3">No upcoming deadlines set.</div>
              </div>
            </section>
          </main>
        </div>
      </div>
    </div>
  </body>
</html>
