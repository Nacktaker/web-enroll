@extends('layouts.main')

@section('title', 'Home')

@section('content')
<div class="ad-section">
    <img src="https://scontent.fcnx1-1.fna.fbcdn.net/v/t39.30808-6/486653676_1073360408163508_5698905431189613531_n.jpg?_nc_cat=100&ccb=1-7&_nc_sid=86c6b0&_nc_eui2=AeEDKh5xI_tBU5wS_ubJXmGg7OmdIGw6J3Hs6Z0gbDoncXZ9GAJePl6MJgvMGWa8v36z_C3z0hXfZb1huUq675Rs&_nc_ohc=lf_tdzyA7CIQ7kNvwEc8pi5&_nc_oc=AdnxDu6TDazYwpwQgRAsZvQq0Iv45uDE_vot1ZPg-Tt7zyVzQ6CLIGUj169j3_VYw8g&_nc_zt=23&_nc_ht=scontent.fcnx1-1.fna&_nc_gid=bB5TXHlj8k6nwE4vpodsHw&oh=00_AfclufsfOo-ZMsWQwTidW5xTmapTycyCHy8vMDi9e5_Kdg&oe=68FAA7AB" alt="">
</div>

<div class="time-section">
    <div id="clock"class="time-display"><script>
    // สร้างฟังก์ชันเพื่ออัปเดตเวลา
    function updateTime() {
        // สร้าง Object วันที่ใหม่
        const now = new Date();

        // จัดรูปแบบ (เติม 0 ข้างหน้าถ้าเลขหลักเดียว)
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');
        const seconds = String(now.getSeconds()).padStart(2, '0');
        
        const day = String(now.getDate()).padStart(2, '0');
        const month = String(now.getMonth() + 1).padStart(2, '0'); // เดือนใน JS เริ่มที่ 0
        const year = now.getFullYear();

        // รวมร่างเป็นรูปแบบที่คุณต้องการ
        const timeString = `${day}/${month}/${year} ${hours}:${minutes}:${seconds}`;

        // นำเวลาไปแสดงใน <div> ที่เราสร้างไว้
        document.getElementById('clock').innerHTML = timeString;
    }

    // เรียกใช้ฟังก์ชัน updateTime ทันที 1 ครั้ง
    updateTime();

    // ตั้งค่าให้เรียกใช้ฟังก์ชัน updateTime ซ้ำทุกๆ 1 วินาที (1000 ms)
    setInterval(updateTime, 1000);
</script></div>
    @php
        // Get student record for current user
        $student = null;
        if (Auth::check() && Auth::user()->role === 'STUDENT') {
            $student = \App\Models\Student::where('u_id', Auth::id())->first();
        }
    @endphp

    @if($student)
        <button class="search-btn">
            <a href="{{ route('students.show-schedule', ['id' => $student->id]) }}">ดูตารางเรียน</a>
        </button>
    @endif
</div>

@php
    // Get enrollment period from database
    $settings = \App\Models\EnrollmentSetting::getCurrentPeriod();
    $startDate = $settings->start_date->format('d/m/Y');
    $endDate = $settings->end_date->format('d/m/Y');
@endphp

<div class="controls">
    @can('adminMenu', Auth::user())
        <a href="{{ route('settings.enrollment.edit') }}" class="control-btn">
            ช่วงเวลารับ: {{ $startDate }}
            <br>
            <small>(คลิกเพื่อแก้ไข)</small>
        </a>
        <a href="{{ route('settings.enrollment.edit') }}" class="control-btn">
            ช่วงเวลาสิ้นสุด: {{ $endDate }}
            <br>
            <small>(คลิกเพื่อแก้ไข)</small>
        </a>
    @else
        <button class="control-btn">ช่วงเวลารับ: {{ $startDate }}</button>
        <button class="control-btn">ช่วงเวลาสิ้นสุด: {{ $endDate }}</button>
    @endcan
    @can('adminMenu', Auth::user())
        <button class="search-btn"><a href="{{ route('users.create') }}">เพิ่ม User</a></button>
    @endcan
</div>

@can('adminMenu', Auth::user())
    <div class="users">
        <button class="user-box"><a href="{{ route('students.list') }}">นักศึกษา</a></button>
        <button class="user-box"><a href="{{ route('teachers.list') }}">อาจารย์</a></button>
        <button class="user-box"><a href="{{ route('users.list') }}">ผู้ใช้</a></button>
    </div>
@endcan

@can('teacherMenu', Auth::user())
    @php
        $teacher = \App\Models\Teacher::where('u_id', Auth::id())->with('subjects')->first();
    @endphp
    <div class="users">
        @if($teacher && $teacher->subjects->isNotEmpty())
            @foreach($teacher->subjects as $sub)
                <button class="user-box"><a href="{{ route('subjects.students', ['subject' => $sub->subject_id]) }}">{{ $sub->subject_name }}</a></button>
            @endforeach
        @else
            <p>ยังไม่มีวิชาที่สอน</p>
        @endif
    </div>
@endcan
@endsection