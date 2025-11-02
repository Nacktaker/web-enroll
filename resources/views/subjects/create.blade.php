@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.subjects.create', request()->fullUrl());
@endphp
<div class="container">
    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h2>สร้างวิชาใหม่</h2>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('subjects.store') }}">
        @csrf

        <div class="form-group">
            <label for="subject_id">รหัสวิชา</label>
            <input type="text" name="subject_id" id="subject_id" value="{{ old('subject_id') }}" required>
        </div>

        <div class="form-group">
            <label for="subject_name">ชื่อวิชา</label>
            <input type="text" name="subject_name" id="subject_name" value="{{ old('subject_name') }}" required>
        </div>

        <div class="form-group">
            <label for="subject_description">รายละเอียดวิชา</label>
            <textarea name="subject_description" id="subject_description" rows="4">{{ old('subject_description') }}</textarea>
        </div>

        <div class="form-group">
            <label for="subject_place">สถานที่</label>
            <input type="text" name="subject_place" id="subject_place" value="{{ old('subject_place') }}">
        </div>

        <div class="form-group">
            <label for="subject_day">วันเรียน</label>
            <input type="text" name="subject_day" id="subject_day" value="{{ old('subject_day') }}">
        </div>

        <div class="form-group">
            <label for="subject_start_time">เวลาเริ่ม (HH:MM)</label>
            <input type="time" name="subject_start_time" id="subject_start_time" value="{{ old('subject_start_time') }}">
        </div>

        <div class="form-group">
            <label for="subject_end_time">เวลาจบ (HH:MM)</label>
            <input type="time" name="subject_end_time" id="subject_end_time" value="{{ old('subject_end_time') }}">
        </div>

        @can('adminMenu', $user)
            <div class="form-group">
                <label for="teacher_code">ครูประจำวิชา</label>
                <select name="teacher_code" id="teacher_code" required>
                    <option value="">-- เลือกอาจารย์ --</option>
                    @foreach($teachers as $t)
                        <option value="{{ $t->teacher_code }}" {{ old('teacher_code') == $t->teacher_code ? 'selected' : '' }}>
                            {{ optional($t->user)->firstname }} {{ optional($t->user)->lastname }} ({{ $t->teacher_code }})
                        </option>
                    @endforeach
                </select>
            </div>
        @endcan

        @can('teacherMenu', $user)
            {{-- Teacher creates for themselves; send hidden teacher_code --}}
            <input type="hidden" name="teacher_code" value="{{ $teacher_code }}">
            <p>สร้างโดยอาจารย์: {{ optional($user)->name }}</p>
        @endcan

        <div class="form-group">
            <button type="submit">สร้างวิชา</button>
        </div>
    </form>
</div>
@endsection
