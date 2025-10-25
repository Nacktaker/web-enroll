
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
        <nav class="nav">@auth
            <ul>
                <li><a href="{{ route('students.add-subject-form') }}">เพิ่มวิชาเรียน</a></li>
                <li><a href="{{ route('subjects.list') }}">ดูวิชาที่เปิดสอบ</a></li>
                <li><a href="#">ยืนยันลงทะเบียน</a></li>
                <li><a href="#">ยืนยันการตรวจสอบ</a></li>
            </ul>
        </nav>
        <div class="user-info">
            
            <form action="{{ route('logout') }}" method="post">

                @csrf

                <a href=""><span>{{ \Auth::user()->name }}</span></a>
                <span>{{ \Auth::user()->role }}</span>

                <button type="submit">Logout</button>

            </form>
            @endauth
            @guest
            <a href="{{ route('login') }}">LOGIN</a>
            @endguest
        </div>
    </header>

    <main class="main-content">
        @yield('content')
    </main>
</body>
    <footer>
        <small>&copy; {{ date('Y') }} My Website</small>
    </footer>

</html>
<?php
//{{ route('users.selves.selves-view')}}