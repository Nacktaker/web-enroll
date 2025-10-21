@extends('subjects.main', [
    'title' => $subject->subject_name,
])

@section('content')
    <dl class="app-cmp-data-detail">
        <dt>Subject Code</dt>
        <dd>
            <span class="app-cl-code">{{ $subject->subject_id }}</span>
        </dd>

        <dt>Subject Nmae</dt>
        <dd>
            {{ $subject->subject_name }}
        </dd>

        <dt>Teacher</dt>
        <dd>
            <span style="display: inline-block; width: 10ch;" class="app-cl-number">{{ $subject->subject_name }}</span>
        </dd>
    </dl>

@endsection