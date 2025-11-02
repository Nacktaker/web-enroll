@extends('layouts.main', ['title' => 'Subjects List'])

@section('content')
@php
// Store current URL for back navigation from view and create pages
session()->put('bookmarks.subjects.view', url()->full());
session()->put('bookmarks.subjects.create', url()->full());
@endphp
<search>
    <form action="{{ route('subjects.list') }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail">
            <label for="app-criteria-term">Search</label>
            <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] }}" />
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit" class="primary">Search</button>
            <a href="{{ route('subjects.list') }}">
                <button type="button" class="accent">X</button>
            </a>
        </div>
    </form>
</search>
<div class="page-header" style="text-align: center; margin: 20px 0;">
    <h2 style="color: #333; font-size: 24px;">ดูวิชาที่เปิดสอน</h2>
</div>
<div class="mb-3">
    <a href="{{ url()->previous() }}" class="btn btn-secondary">กลับ</a>
</div>
<table>
    <thead>
        <tr>
            <th>Subject ID</th>
            <th>Name</th>
            <th>Room</th>
            <th>Day</th>
            <th>Start</th>
            <th>End</th>
            <th>Teacher</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($subjects as $subject)
        <tr>
            <td>
                <a href="{{ route('subjects.view', ['subject' => $subject->subject_id]) }}">
                    {{ $subject->subject_id }}
                </a>
            </td>
            <td>{{ $subject->subject_name }}</td>
            <td>{{ $subject->subject_place }}</td>
            <td>{{ $subject->subject_day  }}</td>
            <td>{{ $subject->subject_start_time  }}</td>
            <td>{{ $subject->subject_end_time  }}</td>
            <td>@if ($subject->teacher && $subject->teacher->user){{ $subject->teacher->user->firstname }}@else<span style="color: grey;">(ยังไม่มีผู้สอน)</span>@endif</td>


        </tr>
        @endforeach
    </tbody>
</table>
<div style="margin-top:12px;">
            {{ $subjects->links() }}
        </div>
@endsection