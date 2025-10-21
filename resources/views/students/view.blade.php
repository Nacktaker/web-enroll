@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Student: {{ $student->name }}</h1>

    <dl>
        <dt>ID</dt>
        <dd>{{ $student->id }}</dd>

        <dt>Name</dt>
        <dd>{{ $student->name }}</dd>

        <dt>Email</dt>
        <dd>{{ $student->email }}</dd>
    </dl>

    <p><a href="{{ route('students.index') }}">Back to list</a> | <a href="{{ route('students.edit', $student->id) }}">Edit</a></p>
    <form method="POST" action="{{ route('students.destroy', $student->id) }}" onsubmit="return confirm('Are you sure you want to delete this student?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:#c00;">Delete Student</button>
    </form>
</div>
@endsection
