@extends('layouts.main')

@section('title', 'รายชื่อนักศึกษาที่ลงวิชา')

@section('content')
<div class="container">
    <div class="mb-3">
        <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h2>รายชื่อนักศึกษาที่ลงวิชา: {{ $subject->subject_name }} ({{ $subject->subject_id }})</h2>

    @if($students->isEmpty())
        <p>ยังไม่มีนักศึกษาลงทะเบียนในวิชานี้</p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #ddd;">
                    <th>รหัสนักศึกษา</th>
                    <th>ชื่อ</th>
                    <th>สาขา/คณะ</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $stu)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $stu->stu_code }}</td>
                        <td>{{ optional($stu->user)->firstname }} {{ optional($stu->user)->lastname }}</td>
                        <td>{{ $stu->faculty ?? '' }} / {{ $stu->department ?? '' }}</td>
                        <td>
                            @if(optional($stu)->id)
                                <a href="{{ route('students.show', ['id' => $stu->id]) }}">ดูโปรไฟล์</a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
