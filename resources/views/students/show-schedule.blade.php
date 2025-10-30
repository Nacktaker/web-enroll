@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.students.schedule', url()->full());
@endphp
<div class="page-header" style="text-align: center; margin: 20px 0;">
    <h2 style="color: #333; font-size: 24px;">ตารางเรียน</h2>
</div>

<div class="container">@auth
    <form action="{{ route('students.removewaiting', [
            'id' => \Auth::user()->id]
        ) }}"
            id="add-form-remove-waiting" method="post">@csrf</form>
    
@endauth


   

     @if($studentsubjects->isEmpty())
        <p>No Enrollment found.</p>
    @else
        <h3>Subjests List</h3>
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
                @foreach($studentsubjects as $s)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $s->subject_id }}</td>
                            <td>{{ $s->subject->subject_name ?? '' }}</td>
                            <td>{{ $s->subject->subject_place ?? '' }}</td>
                            <td>{{ $s->subject->subject_day ?? '' }}</td>
                            <td>{{ $s->subject->subject_start_time ?? '' }}</td>
                            <td>{{ $s->subject->subject_end_time ?? '' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endif

    <div style="margin-top: 20px;">
        <a href="{{ session()->get('bookmarks.students.view', route('students.show', ['id' => $students->id])) }}" class="btn btn-secondary">กลับ</a>
    </div>
</div>

@endsection
