<!DOCTYPE html>
<html>
<head>
    <title>Fil Products Staff Login</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            height: 100vh;
            display: flex;
            background: linear-gradient(135deg, #003366, #001f3f);
        }

        /* LEFT SIDE (BRANDING) */
        .left {
            flex: 1;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
        }

        .left h1 {
            font-size: 40px;
            margin-bottom: 10px;
        }

        .left p {
            opacity: 0.8;
        }

        /* RIGHT SIDE (LOGIN BOX) */
        .right {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f4f6f9;
        }

        .login-box {
            background: white;
            padding: 35px;
            border-radius: 12px;
            width: 320px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        }

        .login-box h2 {
            margin-bottom: 20px;
            color: #003366;
            text-align: center;
        }

        input {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: 0.2s;
        }

        input:focus {
            border-color: #003366;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background: #003366;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #002244;
        }

        .error {
            color: red;
            margin-top: 10px;
            text-align: center;
        }

        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .left {
                display: none;
            }

            .right {
                flex: 1;
            }
        }
    </style>
</head>

<body>

<!-- LEFT SIDE -->
<div class="left">
    <h1>Fil Products Samar</h1>
    <p>Internet & Cable TV Provider</p>
</div>

<!-- RIGHT SIDE -->
<div class="right">

    <div class="login-box">
        <h2>Staff Login</h2>

        <form method="POST" action="{{ route('staff.login') }}">
            @csrf

            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Login</button>
        </form>

        @if(session('error'))
            <p class="error">{{ session('error') }}</p>
        @endif

        @if(session('success'))
            <p class="success">{{ session('success') }}</p>
        @endif
    </div>

</div>

</body>
</html>