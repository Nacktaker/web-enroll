@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Edit Student</h1>

    @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #fbd5d5;padding:8px;margin-bottom:12px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.update', $student->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="firstname">First name</label>
            <input id="firstname" name="firstname" type="text" value="{{ old('firstname', $student->user->firstname ?? '') }}" required />
        </div>
        <div>
            <label for="lastname">Last name</label>
            <input id="lastname" name="lastname" type="text" value="{{ old('lastname', $student->user->lastname ?? '') }}" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $student->user->email ?? '') }}" required />
        </div>
        <div>
            <label for="stu_code">Student code</label>
            <input id="stu_code" name="stu_code" type="text" value="{{ old('stu_code', $student->stu_code) }}" />
        </div>
        <div>
            <label for="faculty">Faculty</label>
            <input id="faculty" name="faculty" type="text" value="{{ old('faculty', $student->faculty) }}" />
        </div>
        <div>
            <label for="department">Department</label>
            <input id="department" name="department" type="text" value="{{ old('department', $student->department) }}" />
        </div>
        <div>
            <label for="year">Year</label>
            <input id="year" name="year" type="text" value="{{ old('year', $student->year) }}" />
        </div>
        <div style="margin-top:12px;">
            <button type="submit">Save</button>
            <a href="{{ route('students.show', $student->id) }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
