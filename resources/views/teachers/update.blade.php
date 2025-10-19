@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Teacher</h1>

    @if($errors->any())
        <div style="background:#fff5f5;border:1px solid #fbd5d5;padding:8px;margin-bottom:12px;">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('teachers.update', $teacher->id) }}">
        @csrf
        @method('PUT')
        <div>
            <label for="name">Name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $teacher->name) }}" required />
        </div>
        <div>
            <label for="email">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $teacher->email) }}" required />
        </div>
        <div style="margin-top:12px;">
            <button type="submit">Save</button>
            <a href="{{ route('teachers.show', $teacher->id) }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
