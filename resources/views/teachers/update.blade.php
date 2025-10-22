@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Edit Teacher</h1>

    @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #fbd5d5;padding:8px;margin-bottom:12px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="firstname">First name</label>
            <input id="firstname" name="firstname" type="text" value="{{ old('firstname', $teacher->user->firstname ?? '') }}" required />
        </div>
        <div>
            <label for="lastname">Last name</label>
            <input id="lastname" name="lastname" type="text" value="{{ old('lastname', $teacher->user->lastname ?? '') }}" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $teacher->user->email ?? '') }}" required />
        </div>
        <div>
            <label for="teacher_code">Teacher code</label>
            <input id="teacher_code" name="teacher_code" type="text" value="{{ old('teacher_code', $teacher->teacher_code) }}" />
        </div>
        <div>
            <label for="faculty">Faculty</label>
            <input id="faculty" name="faculty" type="text" value="{{ old('faculty', $teacher->faculty) }}" />
        </div>
        <div style="margin-top:12px;">
            <button type="submit">Save</button>
            <a href="{{ route('teachers.show', $teacher->id) }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
