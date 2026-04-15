<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>

    <style>
        body {
            margin: 0;
            font-family: 'Segoe UI', Arial, sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #003366, #001f3f);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-box {
            background: white;
            padding: 40px;
            border-radius: 12px;
            width: 340px;
            box-shadow: 0 15px 40px rgba(0,0,0,0.2);
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
            color: #003366;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            outline: none;
            transition: 0.2s;
        }

        input:focus {
            border-color: #003366;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #003366;
            border: none;
            border-radius: 6px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: 0.2s;
        }

        button:hover {
            background: #002244;
        }

        .error {
            color: red;
            margin-bottom: 10px;
        }

        .brand {
            margin-bottom: 15px;
            font-size: 20px;
            font-weight: bold;
            color: #003366;
        }

        .subtext {
            font-size: 12px;
            color: #777;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="login-box">

    <div class="brand">Fil Products</div>
    <div class="subtext">Admin Management System</div>

    <h2>Login</h2>

    @if(session('error'))
        <p class="error">{{ session('error') }}</p>
    @endif

    <form method="POST" action="{{ route('admin.login') }}">
        @csrf

        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>

        <button type="submit">Login</button>
    </form>

</div>

</body>
</html>