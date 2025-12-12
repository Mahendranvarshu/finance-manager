<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body { 
            font-family: Arial, sans-serif; 
            background: #f8fafc; 
            display: flex; 
            height: 100vh; 
            align-items: center;
            justify-content: center;
        }
        .login-box {
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 12px rgba(0,0,0,0.07);
            padding: 32px 24px;
            width: 350px;
        }
        .login-box h2 {
            margin-bottom: 24px;
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            color: #555;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 10px 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .btn-login {
            width: 100%;
            padding: 10px;
            color: #fff;
            background: #2563eb;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            font-size: 1em;
        }
        .btn-login:hover {
            background: #1d4ed8;
        }
        .error {
            color: #b91c1c;
            background: #fee2e2;
            padding: 8px 10px;
            border-radius: 4px;
            margin-bottom: 16px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Admin Login</h2>
        @if(session('error'))
            <div class="error">
                {{ session('error') }}
            </div>
        @endif
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email" 
                    required 
                    autofocus
                    value="{{ old('email') }}"
                >
                @if($errors->has('email'))
                    <div class="error">Invalid login credentials.</div>
                @endif
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input 
                    type="password"
                    name="password"
                    id="password"
                    required
                >
                @error('password')
                    <div class="error">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>
    </div>
</body>
</html>
