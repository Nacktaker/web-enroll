@extends('layouts.main')

@section('content')
@php
    session()->put('bookmarks.view', request()->fullUrl());
@endphp
<div class="container">
    <div class="mb-3">
        <a href="{{ route('home') }}" class="btn btn-secondary">กลับ</a>
    </div>
    <h1>Users</h1>
    <form action="{{ route('users.list') }}" method="get" style="margin-bottom:10px;">
        <input type="text" name="term" placeholder="Search users..." value="{{ $criteria['term'] ?? '' }}" />
        <button type="submit">Search</button>
        <a href="{{ route('users.list') }}"><button type="button">Clear</button></a>
    </form>

    @if($users->isEmpty())
        <p>No users found.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Created</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $u)
                    <tr>
                        <td><a href="{{ route('users.view', $u->id) }}">{{ $u->id }}</a></td>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->created_at->format('Y-m-d') }}</td>
                        <td><a href="{{ route('users.view', $u->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
