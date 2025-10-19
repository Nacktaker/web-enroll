<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <form action="{{ route('authenticate') }}" method="post">

        @csrf
        <label><h1>Login</h1></label>

        <label>

            E-mail <input type="email" name="email" required />

        </label><br />

        <label>

            Password <input type="password" name="password" required />

        </label><br />

        <button type="submit">Login</button>

        <a href="{{ url('auth/register') }}">Register</a>

         <div class="app-cmp-notifications">

            @error('credentials')

            <div role="alert">

                {{ $message }}

            </div>

            @enderror

        </div>

    </form>
</body>


</html>