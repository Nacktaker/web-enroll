@extends('layouts.main')

@section('content')
<div class="container">
    

    @if($subjects->isEmpty())
        <p>No students found.</p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #ddd;">
                    <th>Subject code</th>
                    <th>Subject name</th>
                    <th>Room</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $s)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $s->subject_id }}</td>
                            <td>{{ $s->subject_name ?? '' }}</td>
                            <td>{{ $s->subject_place ?? '' }}</td>
                            <td>{{ $s->subject_day ?? '' }}</td>
                            <td>{{ $s->subject_start_time ?? '' }}</td>
                            <td>{{ $s->subject_end_time ?? '' }}</td>
                        <td><a href="">Add</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection

<?php
//route('students.add-subject')
?>