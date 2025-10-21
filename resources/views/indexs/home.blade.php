@extends('layouts.main')

@section('title', 'Home')

@section('content')
<div class="ad-section">
    <img src="https://scontent.fcnx1-1.fna.fbcdn.net/v/t39.30808-6/486653676_1073360408163508_5698905431189613531_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEDKh5xI_tBU5wS_ubJXmGg7OmdIGw6J3Hs6Z0gbDoncXZ9GAJePl6MJgvMGWa8v36z_C3z0hXfZb1huUq675Rs&_nc_ohc=lf_tdzyA7CIQ7kNvwEc8pi5&_nc_oc=AdnxDu6TDazYwpwQgRAsZvQq0Iv45uDE_vot1ZPg-Tt7zyVzQ6CLIGUj169j3_VYw8g&_nc_zt=23&_nc_ht=scontent.fcnx1-1.fna&_nc_gid=bB5TXHlj8k6nwE4vpodsHw&oh=00_AfclufsfOo-ZMsWQwTidW5xTmapTycyCHy8vMDi9e5_Kdg&oe=68FAA7AB" alt="">
</div>

<div class="time-section">
    <div class="time-display">{{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</div>
    <button class="search-btn">ค้นหากระบวนวิชาที่เปิดสอบ</button>
</div>

@php
    // Calculate the start and end dates
    $startDate = \Carbon\Carbon::now()->addWeeks(2)->format('d/m/Y');
    $endDate = \Carbon\Carbon::now()->addWeeks(4)->format('d/m/Y');
@endphp

<div class="controls">
    <button class="control-btn">ช่วงเวลารับ: {{ $startDate }}</button>
    <button class="control-btn">ช่วงเวลาสิ้นสุด: {{ $endDate }}</button>
    <button class="search-btn"><a href="{{ route('users.adduser') }}">เพิ่ม User</a></button>
</div>

<div class="users">
    <button class="user-box"><a href="{{ route('students.list') }}">นักศึกษา</a></button>
    <button class="user-box"><a href="{{ route('teachers.list') }}">อาจารย์</a></button>
    <button class="user-box"><a href="{{ route('users.list') }}">ผู้ใช้x</a></button>
</div>
@endsection