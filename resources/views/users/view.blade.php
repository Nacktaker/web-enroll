@extends('layouts.main')

@section('content')
<div class="container">
    <h1>User: {{ $user->name }}</h1>

    <dl>
        <dt>ID</dt>
        <dd>{{ $user->id }}</dd>

        <dt>First name</dt>
        <dd>{{ $user->firstname }}</dd>

        <dt>Last name</dt>
        <dd>{{ $user->lastname }}</dd>

        <dt>Email</dt>
        <dd>{{ $user->email }}</dd>

        <dt>Role</dt>
        <dd>{{ $user->role }}</dd>

        <dt>Created</dt>
        <dd>{{ $user->created_at }}</dd>

        <dt>Updated</dt>
        <dd>{{ $user->updated_at }}</dd>
    </dl>

    <button><a href="{{ url('/users') }}">Back to list</a></button>
    <a href="{{ route('users.edit', $user->id) }}">
    <button type="button">Edit User</button>
</a>
    <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:#c00;">Delete User</button>
    </form>
</div>
@endsection
