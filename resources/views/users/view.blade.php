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

    <button><a href="{{ session()->get('bookmarks.view', route('users.list')) }}">Back to list</a></button>

    @can('adminMenu', Auth::user())
        <a href="{{ route('users.edit', $user->id) }}">
            <button type="button">Edit User</button>
        </a>

        <form method="POST" action="{{ route('users.destroy', $user->id) }}" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline-block;margin-left:8px;">
            @csrf
            @method('DELETE')
            <button type="submit" style="color:#c00;">Delete User</button>
        </form>
    @endcan
</div>
@endsection
