@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.students.schedule', request()->fullUrl());
@endphp
<div class="page-header" style="text-align: center; margin: 20px 0;">
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h2 style="color: #333; font-size: 24px;">ดูวิชาที่เปิดสอน</h2>
</div>

<div class="container">@auth
    <form action="{{ route('students.removewaiting', [
            'id' => \Auth::user()->id]
        ) }}"
            id="add-form-remove-waiting" method="post">@csrf</form>
    
<form action="{{ route('students.dropwaiting', [
            'id' => \Auth::user()->id]
        ) }}"
            id="add-form-drop-waiting" method="post">@csrf</form>@endauth


<table> 
    @if($pensubjects->isEmpty())
        <p>No Enrollment found.</p>
    @else
        <h3>Waiting List</h3>
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
                @foreach($pensubjects as $s)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $s->subject_id }}</td>
                            <td>{{ $s->subject->subject_name ?? '' }}</td>
                            <td>{{ $s->subject->subject_place ?? '' }}</td>
                            <td>{{ $s->subject->subject_day ?? '' }}</td>
                            <td>{{ $s->subject->subject_start_time ?? '' }}</td>
                            <td>{{ $s->subject->subject_end_time ?? '' }}</td>
                        <td><button type="submit" form="add-form-remove-waiting" name="sub"
                            value="{{ $s->id }}">remove</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>


    @endif

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
                        <td><button type="submit" form="add-form-drop-waiting" name="sub"
                            value="{{ $s->subject_id }}">Drop</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
@endif
</div>


@endsection
