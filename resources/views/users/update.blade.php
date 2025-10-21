@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/users.css') }}">

<div class="container">
    <h1>Edit User</h1>

    @if($errors->any())
        <div class="errors">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.update', $user->id) }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required />
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required />
        </div>

        <div class="form-group">
            <label for="password">Password 	</label>
            <input id="password" name="password" type="password" />
        </div>

        <div class="form-group">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" />
        </div>

        <div class="form-actions">
            <button type="submit">Save</button>
            <a href="{{ route('users.view', $user->id) }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
