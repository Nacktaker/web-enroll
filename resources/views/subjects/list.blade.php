@extends('subjects.main', [
    'title' => 'Subjects List',
    'mainClasses' => ['app-ly-max-width'],
])

@section('content')
    <table class="app-cmp-data-list">
        <colgroup>
            <col style="width: 5ch;" />
        </colgroup>

        <thead>
            <tr>
                <th>Subject_ID</th>
                <th>Subject_Name</th>
                <th>Room</th>
                <th>Time</th>
                <th>Teacher_FistName</th>
                <th>Teacher_LastName</th>
            </tr>
        </thead>

        <tbody>
            @foreach ($subjects as $subject)
                <tr>
                    <td>
                        <a href="{{ route('subjects.view', [
                            'subject' => $subject->subject_id,
                        ]) }}"
                            class="app-cl-code">
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