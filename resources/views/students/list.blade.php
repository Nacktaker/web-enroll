@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.view', request()->fullUrl());
@endphp
<div class="container">
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h1>Students</h1>
    <form action="{{ route('students.list') }}" method="get" style="margin-bottom:10px;">
        <input type="text" name="term" placeholder="Search students..." value="{{ $criteria['term'] ?? '' }}" />
        <button type="submit">Search</button>
        <a href="{{ route('students.list') }}"><button type="button">Clear</button></a>
    </form>

    <p><a href="{{ route('users.create') }}">Add Student</a></p>

    @if($students->isEmpty())
        <p>No students found.</p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #ddd;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $s)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $s->stu_code }}</td>
                            <td>{{ $s->user ? $s->user->name : ($s->name ?? '') }}</td>
                            <td>{{ $s->user ? $s->user->email : ($s->email ?? '') }}</td>
                        <td><a href="{{ route('students.show', $s->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">
            {{ $students->links() }}
        </div>
    @endif
</div>
@endsection
