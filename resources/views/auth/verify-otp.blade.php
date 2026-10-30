<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Inventory Sales System</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f0ff;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        .container {
            background: white;
            width: 100%;
            max-width: 430px;
            padding: 35px;
            border-radius: 15px;
            box-shadow: 0 8px 30px rgba(50, 30, 100, 0.12);
            text-align: center;
        }

        h2 {
            color: #5426a8;
            margin-bottom: 10px;
        }

        p {
            color: #666;
            line-height: 1.6;
        }

        .email {
            color: #5426a8;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        input {
            width: 100%;
            padding: 14px;
            margin: 18px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 24px;
            text-align: center;
            letter-spacing: 8px;
        }

        button {
            width: 100%;
            padding: 13px;
            background: #652fc1;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-bottom: 12px;
        }

        button:hover {
            background: #4d2099;
        }

        .resend-button {
            background: #eee8ff;
            color: #5426a8;
        }

        .resend-button:hover {
            background: #ded2ff;
        }

        .message {
            padding: 10px;
            border-radius: 7px;
            margin: 12px 0;
            font-size: 14px;
        }

        .success {
            background: #e5f8eb;
            color: #176b35;
        }

        .error {
            background: #ffe8e8;
            color: #a32222;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Verify Your Email</h2>

        <p>
            We sent a 6-digit verification code to:
        </p>

        <p class="email">{{ $user->email }}</p>

        <p>
            Enter the code below. It expires after 5 minutes.
        </p>

        @if(session('success'))
            <div class="message success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="message error">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="message error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('otp.verify') }}">
            @csrf

            <input
                type="text"
                name="otp"
                placeholder="000000"
                maxlength="6"
                inputmode="numeric"
                pattern="[0-9]{6}"
                autocomplete="one-time-code"
                required
            >

            <button type="submit">Verify OTP</button>
        </form>

        <form method="POST" action="{{ route('otp.resend') }}">
            @csrf
            <button type="submit" class="resend-button">
                Resend OTP
            </button>
        </form>
    </div>
</body>
</html>
