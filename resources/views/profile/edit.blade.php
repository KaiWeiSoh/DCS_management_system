<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Edit Profile</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen">
    <div class="max-w-3xl mx-auto p-6">
      <div class="bg-white rounded shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>

        @if(session('success'))
          <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
          @csrf
          <div class="grid grid-cols-1 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Profile picture</label>
              <input type="file" name="profile_picture" accept="image/*" class="mt-1">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Name</label>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="mt-1 block w-full border rounded p-2">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Gender</label>
              <select name="gender" class="mt-1 block w-full border rounded p-2">
                <option value="">-- Select --</option>
                <option value="male" {{ (old('gender', $user->gender) == 'male') ? 'selected' : '' }}>Male</option>
                <option value="female" {{ (old('gender', $user->gender) == 'female') ? 'selected' : '' }}>Female</option>
                <option value="other" {{ (old('gender', $user->gender) == 'other') ? 'selected' : '' }}>Other</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Contact number</label>
              <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}" class="mt-1 block w-full border rounded p-2">
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Project</label>
              <select name="project" id="projectSelect" class="mt-1 block w-full border rounded p-2">
                <option value="">-- Select Project --</option>
                <option value="Project" {{ (old('project', $user->project) == 'Project') ? 'selected' : '' }}>Project</option>
                <option value="FYP I" {{ (old('project', $user->project) == 'FYP I') ? 'selected' : '' }}>FYP I</option>
                <option value="FYP II" {{ (old('project', $user->project) == 'FYP II') ? 'selected' : '' }}>FYP II</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Programme</label>
              <select name="programme" id="programmeSelect" class="mt-1 block w-full border rounded p-2">
                <option value="">-- Select Programme --</option>
                <option value="DIT" data-projects="FYP I,FYP II" {{ (old('programme', $user->programme) == 'DIT') ? 'selected' : '' }}>DIT</option>
                <option value="DCS" data-projects="Project" {{ (old('programme', $user->programme) == 'DCS') ? 'selected' : '' }}>DCS</option>
                <option value="BOS" data-projects="FYP I,FYP II" {{ (old('programme', $user->programme) == 'BOS') ? 'selected' : '' }}>BOS</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700">Bio</label>
              <textarea name="bio" rows="4" class="mt-1 block w-full border rounded p-2">{{ old('bio', $user->bio) }}</textarea>
            </div>
          </div>

          <div class="mt-4 flex items-center space-x-3">
            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Save</button>
            <a href="{{ route('profile.show') }}" class="bg-gray-300 px-4 py-2 rounded">Cancel</a>
          </div>
        </form>
      </div>
    </div>

    <script>
      // Function to filter Programme options based on selected Project
      function filterProgrammeOptions() {
        const projectSelect = document.getElementById('projectSelect');
        const programmeSelect = document.getElementById('programmeSelect');
        const selectedProject = projectSelect.value;
        
        // Get all programme options except the first one (placeholder)
        const programmeOptions = programmeSelect.querySelectorAll('option:not([value=""])');
        
        if (!selectedProject) {
          // If no project is selected, show all programmes
          programmeOptions.forEach(option => {
            option.style.display = '';
            option.disabled = false;
          });
          return;
        }
        
        // Filter programmes based on project
        programmeOptions.forEach(option => {
          const allowedProjects = option.getAttribute('data-projects');
          
          if (allowedProjects && allowedProjects.includes(selectedProject)) {
            option.style.display = '';
            option.disabled = false;
          } else {
            option.style.display = 'none';
            option.disabled = true;
          }
        });
        
        // If current programme selection is not valid for the selected project, reset it
        const currentProgramme = programmeSelect.value;
        const currentOption = programmeSelect.querySelector(`option[value="${currentProgramme}"]`);
        if (currentOption && currentOption.disabled) {
          programmeSelect.value = '';
        }
      }
      
      // Add event listener to project select
      document.getElementById('projectSelect').addEventListener('change', filterProgrammeOptions);
      
      // Run on page load to apply initial filtering
      document.addEventListener('DOMContentLoaded', filterProgrammeOptions);
    </script>
  </body>
</html>
