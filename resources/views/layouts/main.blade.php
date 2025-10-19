<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="stylesheet" type="text/css" href="{{ asset('css/common.css') }}" />

    <title>{{ $title }}</title>
</head>

<body>
    <header id="app-cmp-main-header">
        <nav>
            <ul class="app-cmp-links">
                <li>
                    <a href="{{ route('subjects.list') }}">Subjects</a>
                </li>
            </ul>
        </nav>
    </header>

    <main id="app-cmp-main-content" @class($mainClasses ?? [])>
        <header>
            <h1><span @class($titleClesses ?? [])>{{ $title }}</span></h1>
            @section('header')
            @show
        </header>

        @yield('content')
    </main>

    <footer id="app-cmp-main-footer">
        &#xA9; Copyright Pachara's Database.
    </footer>
</body>

</html>