
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
    <header class="header">
    <div class="logo"><a href="{{ url('/home') }}"><img src="https://www.camt.cmu.ac.th/wp-content/uploads/2024/08/logo-camt.svg" alt=""></a></div>
    @auth
    <nav class="nav">
        <ul>
            @can('studentMenu', Auth::user())
                <li><a href="{{ route('students.add-subject-form', ['id' => \Auth::user()->id]) }}">เพิ่มวิชาเรียน</a></li>
                <li><a href="{{ route('subjects.list') }}">ดูวิชาที่เปิดสอบ</a></li>
                <li><a href="{{ route('students.schedule', ['id' => \Auth::user()->id]) }}">ดูวิชาที่ลงทะเบียนเรียน</a></li>
            @endcan

            @can('teacherMenu', Auth::user())
                <li><a href="{{ route('teachers.add-approve-form', ['id' => \Auth::user()->id]) }}">ยืนยันลงทะเบียน</a></li>
                <li><a href="{{ route('teachers.drop-approve-form', ['id' => \Auth::user()->id])  }}">ยืนยันการดรอป</a></li>
                <li><a href="{{ route('subjects.create') }}">สร้างวิชา</a></li>
            @endcan

            @can('adminMenu', Auth::user())
                <li><a href="{{ route('admin.add-approve-form') }}">จัดการการลงทะเบียน</a></li>
                <li><a href="{{ route('admin.drop-approve-form') }}">จัดการการดรอป</a></li>
                <li><a href="{{ route('subjects.create') }}">สร้างวิชา</a></li>
            @endcan
        </ul>
    </nav>
        <div class="user-info">
            
            <form action="{{ route('logout') }}" method="post">

                @csrf

                <span><a href="/self/view">{{ \Auth::user()->name }}</a></span>
                <span> {{ \Auth::user()->role }}</span>

                <button type="submit">Logout</button>

            </form>
            @endauth
            @guest
            <a href="{{ route('login') }}">LOGIN</a>
            @endguest
        </div>
    </header>

    <main class="main-content">
        {{-- Global flash status message --}}
        @if(session('status'))
            <div class="flash-status" style="background:#e6f7e6;border:1px solid #b7e0b7;padding:10px;margin:10px 20px;border-radius:4px;color:#2d6a2d;">
                {{ session('status') }}
            </div>
        @endif

        @yield('content')
    </main>
</body>
    <footer>
        <small>&copy; {{ date('Y') }} My Website</small>
    </footer>

</html>
<?php
//{{ route('users.selves.selves-view')}}