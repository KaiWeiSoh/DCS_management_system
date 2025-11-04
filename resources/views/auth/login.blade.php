<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login & Register Form</title>
    <link rel="stylesheet" href="{{ asset('style.css') }}">
</head>

<body>
    <div class="container">
        <div class="form-box active" id="login-form">
            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <h2>Login</h2>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <button type="submit" name="login">Login</button>
                <p>No registered account?<a href="#" onclick="showForm('register-form')">Register</a></p>
            </form>
        </div>
    </div>

    <div class="form-box" id="register-form">
            <form method="POST" action="{{ route('register.post') }}">
                @csrf
                <h2>Register</h2>
                <input type="text" name="name" placeholder="Name" required>
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="password" placeholder="Password" required>
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required>
                <select name="role" required>
                    <option value="">--Select Role--</option>
                    <option value="student">Student</option>
                    <option value="supervisor">Supervisor</option>
                    <option value="committee">Committee</option>
                </select>
                <button type="submit" name="register">Register</button>
                <p>Already register?<a href="#" onclick="showForm('login-form')">Login</a></p>
            </form>
        </div>

    <script src="{{ asset('script.js') }}"></script>
</body>

</html>