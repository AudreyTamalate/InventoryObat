<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <style>
        body {
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            width: 320px;
        }

        h2 {
            text-align: center;
            margin-bottom: 24px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 6px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            border-radius: 8px;
            border: 1px solid #ccc;
            margin-bottom: 16px;
        }

        .remember {
            display: flex;
            align-items: center;
            margin-bottom: 16px;
        }

        .remember input {
            margin-right: 8px;
        }

        button {
            width: 100%;
            background-color: #d97706;
            color: white;
            border: none;
            padding: 10px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 12px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>

        @if(session('error'))
            <div class="error">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login.process') }}">
            @csrf

            <label for="email">Username*</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Password*</label>
            <input type="password" id="password" name="password" required>

            <div class="remember">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember" style="margin: 0;">Remember me</label>
            </div>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
