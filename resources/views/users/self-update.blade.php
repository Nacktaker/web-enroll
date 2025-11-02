@extends('layouts.main')

@section('content')
<div class="box-view">
    <h2>Edit Profile</h2>

    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach

    <form method="POST" action="{{ route('self.update') }}">
        @csrf
        <div>
            <label>First Name:</label>
            <input type="text" name="firstname" value="{{ old('firstname', $user->firstname) }}" required>
        </div>

        <div>
            <label>Last Name:</label>
            <input type="text" name="lastname" value="{{ old('lastname', $user->lastname) }}" required>
        </div>

        <div>
            <label>Email:</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
        </div>

        <div>
            <label>New Password (optional):</label>
            <input type="password" name="password">
            <input type="password" name="password_confirmation" placeholder="Confirm password">
        </div>

        <button type="submit">Save</button>
    </form>
</div>
@endsection
