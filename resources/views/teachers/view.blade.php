@extends('layouts.main')

@section('content')

<div class="container">
    <h1>Teacher: {{ $teacher->user ? $teacher->user->name : ($teacher->name ?? '') }}</h1>

    <dl>
        <dt>ID</dt>
        <dd>{{ $teacher->id }}</dd>

        <dt>First name</dt>
        <dd>{{ $teacher->user ? $teacher->user->firstname : '' }}</dd>

        <dt>Last name</dt>
        <dd>{{ $teacher->user ? $teacher->user->lastname : '' }}</dd>

        <dt>Email</dt>
        <dd>{{ $teacher->user ? $teacher->user->email : ($teacher->email ?? '') }}</dd>

        <dt>Teacher code</dt>
        <dd>{{ $teacher->teacher_code }}</dd>

        <dt>Faculty</dt>
        <dd>{{ $teacher->faculty }}</dd>
    </dl>

    <p>
        <a href="{{ session()->get('bookmarks.view', route('teachers.list')) }}" class="btn btn-secondary">กลับ</a>
        @can('adminMenu', Auth::user())
            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn btn-warning">แก้ไข</a>
        @endcan
        <a href="{{ route('teachers.view-subjects', $teacher->id) }}" class="btn btn-primary">ดูรายวิชาที่สอน</a>
    </p>

    @can('adminMenu', Auth::user())
        <form method="POST" action="{{ route('teachers.destroy', $teacher->id) }}" onsubmit="return confirm('Are you sure you want to delete this teacher?');">
            @csrf
            @method('DELETE')
            <button type="submit" style="color:#c00;">Delete Teacher</button>
        </form>
    @endcan
</div>
@endsection
