<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Inventory System</title>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f1fb;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .container {
            background: white;
            padding: 30px;
            width: 100%;
            max-width: 420px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        h1 {
            color: #5b21b6;
            text-align: center;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            margin-top: 7px;
            border: 1px solid #ccc;
            border-radius: 7px;
            box-sizing: border-box;
        }

        button {
            width: 100%;
            padding: 13px;
            margin-top: 20px;
            border: none;
            border-radius: 7px;
            background: #5b21b6;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        .captcha {
            margin-top: 20px;
        }

        .error {
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            border-radius: 7px;
            margin-bottom: 12px;
        }

        .link {
            text-align: center;
            margin-top: 20px;
        }

        a {
            color: #5b21b6;
        }

        .remember {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-top: 15px;
        }

        .remember input {
            width: auto;
            margin: 0;
        }

        .remember label {
            margin: 0;
            font-weight: normal;
        }
    </style>
</head>

<body>

<div class="container">

    <h1>Welcome Back</h1>

    @if($errors->any())
        <div class="error">
            @foreach($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if(session('success'))
        <div style="background:#dcfce7;color:#166534;padding:10px;border-radius:7px;">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login.submit') }}">
        @csrf

        <label>Email Address</label>
        <input type="email" name="email"
               value="{{ old('email') }}" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <div class="remember">
            <input type="checkbox" name="remember" id="remember" value="1">
            <label for="remember">Remember me</label>
        </div>

        <div class="captcha">
            <div class="g-recaptcha"
                 data-sitekey="{{ config('recaptcha.site_key') }}">
            </div>
        </div>

        <button type="submit">Login</button>
    </form>

    <div class="link">
        Don't have an account?
        <a href="{{ route('register') }}">Register here</a>
    </div>

</div>

</body>
</html>
