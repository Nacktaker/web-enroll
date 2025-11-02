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
        @php
            
            $backUrl = session()->get('bookmarks.view', route('students.list'));

            
            if (Auth::check() && strtolower((string) (Auth::user()->role ?? '')) === 'teacher') {
               
                $subjectCode = session()->get('bookmarks.subject');
                if (!empty($subjectCode)) {
                    $backUrl = route('subjects.students', ['subject' => $subjectCode]);
                } else {
                    
                    $backUrl = url()->previous();
                }
            }
        @endphp

        <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>

        @can('adminMenu', Auth::user())
            <a href="{{ route('students.edit', $student->id) }}" class="btn btn-warning">แก้ไข</a>
            <a href="{{ route('students.view-subjects', $student->id) }}" class="btn btn-primary">ดูรายวิชาที่ลงทะเบียน</a>
        @endcan
        
    </p>

    @can('adminMenu', Auth::user())
        <form method="POST" action="{{ route('students.destroy', $student->id) }}" onsubmit="return confirm('Are you sure you want to delete this student?');">
            @csrf
            <button type="submit" style="color:#c00;">Delete Student</button>
        </form>
    @endcan
</div>
@endsection
