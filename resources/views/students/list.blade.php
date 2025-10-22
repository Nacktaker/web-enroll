@extends('layouts.main')

@section('content')
<div class="container">
    <h1>Students</h1>
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
    @endif
</div>
@endsection
