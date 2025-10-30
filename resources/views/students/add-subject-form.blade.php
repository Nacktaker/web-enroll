@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.students.add-subject', request()->fullUrl());
@endphp

<div class="page-header" style="text-align: center; margin: 20px 0;">
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h2 style="color: #333; font-size: 24px;">เพิ่มวิชาเรียน</h2>
</div>

<div class="container" style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">@auth
    <form action="{{ route('students.add-subject', [
            'id' => \Auth::user()->id]
        ) }}"
            id="add-form-add-sub" method="post">@csrf</form>@endauth
    

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
                        <td><button type="submit" form="add-form-add-sub" name="sub"
                            value="{{ $s->subject_id }}">Add</button></td>
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