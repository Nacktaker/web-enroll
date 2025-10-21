@extends('layouts.main')

@section('content')
<div class="container">
    <h1>User: {{ $user->name }}</h1>

    <dl>
        <dt>ID</dt>
        <dd>{{ $user->id }}</dd>

        <dt>Name</dt>
        <dd>{{ $user->name }}</dd>

        <dt>Email</dt>
        <dd>{{ $user->email }}</dd>

        <dt>Created</dt>
        <dd>{{ $user->created_at }}</dd>
    </dl>

    <p><a href="{{ url('/users') }}">Back to list</a></p>
</div>
@endsection
