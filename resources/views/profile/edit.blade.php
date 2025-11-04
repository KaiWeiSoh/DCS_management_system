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
              <label class="block text-sm font-medium text-gray-700">Subject course</label>
              <input type="text" name="subject_course" value="{{ old('subject_course', $user->subject_course) }}" class="mt-1 block w-full border rounded p-2">
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
  </body>
</html>
