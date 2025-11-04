<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Register</title>
    <link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">
  </head>
  <body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
      <div class="bg-white shadow rounded p-6">
        <h2 class="text-2xl font-semibold mb-4">Register</h2>

        @if($errors->any())
          <div class="mb-4 text-red-600">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('register.post') }}">
          @csrf
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="mt-1 block w-full border rounded p-2">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="mt-1 block w-full border rounded p-2">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" required class="mt-1 block w-full border rounded p-2">
          </div>
          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="mt-1 block w-full border rounded p-2">
          </div>

          <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" required class="mt-1 block w-full border rounded p-2">
              <option value="student" {{ old('role') == 'student' ? 'selected' : '' }}>Student</option>
              <option value="supervisor" {{ old('role') == 'supervisor' ? 'selected' : '' }}>Supervisor</option>
              <option value="committee" {{ old('role') == 'committee' ? 'selected' : '' }}>Committee</option>
            </select>
          </div>

          <div class="flex items-center justify-between">
            <button class="bg-green-600 text-white px-4 py-2 rounded">Register</button>
            <a href="{{ route('login') }}" class="text-sm text-gray-600">Already registered?</a>
          </div>
        </form>
      </div>
    </div>
  </body>
</html>
