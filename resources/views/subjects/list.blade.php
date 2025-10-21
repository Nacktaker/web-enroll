@extends('layouts.main', ['title' => 'Subjects List'])

@section('content')
<table>
    <thead>
        <tr>
            <th>Subject ID</th>
            <th>Name</th>
            <th>Room</th>
            <th>Time</th>
            <th>Teacher First Name</th>
            <th>Teacher Last Name</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subjects as $subject)
            <tr>
                <td>
                    <a href="{{ route('subjects.view', ['subject' => $subject->subject_id]) }}">
                        {{ $subject->subject_id }}
                    </a>
                </td>
                <td>{{ $subject->subject_name }}</td>
                <td>{{ $subject->subject_place }}</td>
                <td>{{ $subject->subject_time }}</td>
                <td>{{ $subject->teacher_first_name }}</td>
                <td>{{ $subject->teacher_last_name }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
