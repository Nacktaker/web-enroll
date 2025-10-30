@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.teachers.add-approve', request()->fullUrl());
@endphp
<div class="page-header" style="text-align: center; margin: 20px 0;">
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h2 style="color: #333; font-size: 24px;">ยืนยันลงทะเบียน</h2>
</div>

<div class="container">@auth
    <form action="{{ route('teachers.add-approve', ['id' => \Auth::user()->id]) }}" id="add-form-add-sub" method="post">@csrf</form>

    @endauth

    @if($pen->isEmpty())
    <p>No Enrollment found.</p>
    @else
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="text-align:left; border-bottom:1px solid #ddd;">
                <th>studentCode</th>
                <th>student name</th>
                <th>Subject code</th>
                <th>Subject name</th>
                <th>Room</th>
                <th>Day</th>

                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($pen as $s)
            <tr style="border-bottom:1px solid #f1f1f1;">
                <td>{{ $s->stu_id }}</td>
                <td>
                    @if($s->student && $s->student->user)
                        {{ $s->student->user->firstname }} {{ $s->student->user->lastname }}
                    @else
                        <span style="color: #999;">(ไม่พบข้อมูลนักศึกษา)</span>
                    @endif
                </td>
                <td>{{ $s->subject_id }}</td>
                <td>{{ $s->subject->subject_name ?? '' }}</td>
                <td>{{ $s->subject->subject_place ?? '' }}</td>
                <td>{{ $s->subject->subject_day ?? '' }}</td>

                <td><button type="submit" form="add-form-add-sub" name="sub"
                        value="{{ $s->id }}">Confirm</button></td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
</div>
@endsection