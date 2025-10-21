<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <form action="{{ route('authenticate') }}" method="POST">
        @csrf
        <h1>LOGIN</h1>

        {{-- แสดง error เมื่อ login ไม่ผ่าน --}}
        @error('credentials')
        <div role="alert">{{ $message }}</div>
        @enderror

        <label for="email">E-mail</label>
        <input id="email" type="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password</label>
        <input id="password" type="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>

      
    </form>
</body>
</html>
