@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.students.subjects', url()->full());
@endphp
<div class="container">
    <div class="page-header" style="text-align: center; margin: 20px 0;">
        <h2 style="color: #333; font-size: 24px;">วิชาที่นักศึกษาลงทะเบียน</h2>
        <p style="color: #666;">รหัสนักศึกษา: {{ $student->stu_code }} | ชื่อ: {{ $student->user->firstname }} {{ $student->user->lastname }}</p>
    </div>

    @if($enrolledSubjects->isEmpty())
        <p style="text-align: center; color: #666;">ยังไม่มีวิชาที่ลงทะเบียน</p>
    @else
        <table style="width:100%; border-collapse:collapse; margin-top: 20px; background: white; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <thead>
                <tr style="background: #f8f9fa; text-align:left; border-bottom:2px solid #dee2e6;">
                    <th style="padding: 12px 15px;">รหัสวิชา</th>
                    <th style="padding: 12px 15px;">ชื่อวิชา</th>
                    <th style="padding: 12px 15px;">ห้องเรียน</th>
                    <th style="padding: 12px 15px;">วัน</th>
                    <th style="padding: 12px 15px;">เวลาเริ่ม</th>
                    <th style="padding: 12px 15px;">เวลาสิ้นสุด</th>
                    <th style="padding: 12px 15px;">อาจารย์ผู้สอน</th>
                </tr>
            </thead>
            <tbody>
                @foreach($enrolledSubjects as $subject)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td style="padding: 12px 15px;">{{ $subject->subject->subject_id }}</td>
                        <td style="padding: 12px 15px;">{{ $subject->subject->subject_name }}</td>
                        <td style="padding: 12px 15px;">{{ $subject->subject->subject_place }}</td>
                        <td style="padding: 12px 15px;">{{ $subject->subject->subject_day }}</td>
                        <td style="padding: 12px 15px;">{{ $subject->subject->subject_start_time }}</td>
                        <td style="padding: 12px 15px;">{{ $subject->subject->subject_end_time }}</td>
                        <td style="padding: 12px 15px;">
                            @if($subject->subject->teacher && $subject->subject->teacher->user)
                                {{ $subject->subject->teacher->user->firstname }} {{ $subject->subject->teacher->user->lastname }}
                            @else
                                <span style="color: #999;">ยังไม่กำหนด</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div style="margin-top: 20px;">
        <a href="{{ session()->get('bookmarks.students.view', route('students.show', ['id' => $student->id])) }}" class="btn btn-secondary">กลับ</a>
    </div>
</div>
@endsection