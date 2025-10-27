@extends('layouts.main', ['title' => 'Subjects List'])

@section('content')
<table>
    <thead>
        <tr>
            <th>Subject ID</th>
            <th>Name</th>
            <th>Room</th>
            <th>Day</th>
            <th>Start</th>
            <th>End</th>
            <th>Teacher</th>
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
                <td>{{ $subject->subject_day  }}</td>
                <td>{{ $subject->subject_start_time  }}</td>
                <td>{{ $subject->subject_end_time  }}</td>
                <td>@if ($subject->teacher && $subject->teacher->user){{ $subject->teacher->user->firstname }}@else<span style="color: grey;">(ยังไม่มีผู้สอน)</span>@endif</td>


            </tr>
        @endforeach
    </tbody>
</table>
@endsection
