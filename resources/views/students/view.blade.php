@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Student: {{ $student->user ? $student->user->name : ($student->name ?? '') }}</h1>

    <dl>
        <dt>ID</dt>
        <dd>{{ $student->id }}</dd>

        <dt>First name</dt>
        <dd>{{ $student->user ? $student->user->firstname : '' }}</dd>

        <dt>Last name</dt>
        <dd>{{ $student->user ? $student->user->lastname : '' }}</dd>

        <dt>Email</dt>
        <dd>{{ $student->user ? $student->user->email : ($student->email ?? '') }}</dd>

        <dt>Student code</dt>
        <dd>{{ $student->stu_code }}</dd>

        <dt>Faculty</dt>
        <dd>{{ $student->faculty }}</dd>

        <dt>Department</dt>
        <dd>{{ $student->department }}</dd>

        <dt>Year</dt>
        <dd>{{ $student->year }}</dd>
    </dl>

    <p>
        <a href="{{ session()->get('bookmarks.view', route('students.list')) }}" class="btn btn-secondary">กลับ</a>
        <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">แก้ไข</a>
        <a href="{{ route('students.view-subjects', $student->id) }}" class="btn btn-primary">ดูรายวิชาที่ลงทะเบียน</a>
    </p>
    <form method="POST" action="{{ route('students.destroy', $student->id) }}" onsubmit="return confirm('Are you sure you want to delete this student?');">
        @csrf
        @method('DELETE')
        <button type="submit" style="color:#c00;">Delete Student</button>
    </form>
</div>
@endsection
