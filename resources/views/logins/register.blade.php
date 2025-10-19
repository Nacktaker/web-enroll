<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
   
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        @if(session('status'))
            <div class="errors">{{ session('status') }}</div>
        @endif

        @if($errors->any())
            <div class="errors">
                <strong>There were some problems with your input:</strong>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/auth/register') }}">
            @csrf

            <div class="field">
                <label for="name">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus />
            </div>

            <div class="field">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required />
            </div>

            <div class="field">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required />
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required />
            </div>

            <div class="field">
                <button type="submit" class="btn">Register</button>
            </div>
        </form>
    </div>

</body>
</html>