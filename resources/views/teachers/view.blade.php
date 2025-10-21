@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Teacher: {{ $teacher->name }}</h1>

    <dl>
        <dt>ID</dt>
        <dd>{{ $teacher->id }}</dd>

        <dt>Name</dt>
        <dd>{{ $teacher->name }}</dd>

        <dt>Email</dt>
        <dd>{{ $teacher->email }}</dd>
    </dl>

    <p><a href="{{ route('teachers.index') }}">Back to list</a> | <a href="{{ route('teachers.edit', $teacher->id) }}">Edit</a></p>
    <form method="POST" action="{{ route('teachers.destroy', $teacher->id) }}" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:#c00;">Delete Teacher</button>
    </form>
</div>
@endsection
