
@extends('subjects.main', [
    'title' => $subject->subject_name,
])

@section('content')
@php
    session()->put('bookmarks.subjects.view', request()->fullUrl());
@endphp

<div class="mb-3">
    <a href="{{ session()->get('bookmarks.subjects.list', route('subjects.list')) }}" class="btn btn-secondary">กลับ</a>
</div>

<h1>SUBJECT DETAILS</h1>
    <dl class="app-cmp-data-detail">

        <dt>Subject Code</dt>
        <dd>
            <span class="app-cl-code">{{ $subject->subject_id }}</span>
        </dd>

        <dt>Subject Nmae</dt>
        <dd>
            {{ $subject->subject_name }}
        </dd>

    
        <dt>Room</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $subject->subject_place }}</span>

        <dt>Day</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $subject->subject_day  }}</span>
        </dd>

        <dt>Start</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $subject->subject_start_time  }}</span>
        </dd>

        <dt>End</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $subject->subject_end_time  }}</span>
        </dd>
        <dt>Teacher</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $subject->teacher->user->firstname }}</span>
        </dd>
    </dl>

    

@endsection
