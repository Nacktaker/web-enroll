@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="{{ asset('css/self-view.css') }}">



    <h2>User Details</h2>
    <p><strong>ID:</strong> {{ $user->id }}</p>
    <p><strong>Name:</strong> {{ $user->firstname }} {{ $user->lastname }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
    <p><strong>Role:</strong> {{ ucfirst(strtolower($user->role)) }}</p>

    <div class="button-area">
        <a href="{{ session()->get('bookmarks.view', route('home')) }}" class="btn btn-secondary" style="margin-right:8px;">กลับ</a>
        <a href="{{ route('self.update') }}" class="btn-edit">Edit Profile</a>
    </div>
</div>
@endsection
