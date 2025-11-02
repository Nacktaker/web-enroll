@extends('layouts.main')

@section('content')
<search>
    <form action="{{ route('students.add-subject-form', ['id' => \Auth::user()->id]) }}" method="get" class="app-cmp-search-form">
        <div class="app-cmp-form-detail">
            <label for="app-criteria-term">Search</label>
            <input type="text" id="app-criteria-term" name="term" value="{{ $criteria['term'] }}" />
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit" class="primary">Search</button>
            <a href="{{ route('students.add-subject-form', ['id' => \Auth::user()->id]) }}">
                <button type="button" class="accent">X</button>
            </a>
        </div>
    </form>
</search>
<h2 style="align-items: center;">เพิ่มวิชาเรียน</h2>
<div class="container">@auth
    <form action="{{ route('students.add-subject', [
            'id' => \Auth::user()->id]
        ) }}"
            id="add-form-add-sub" method="post">@csrf</form>@endauth
    

    @if($subjects->isEmpty())
        <p>No students found.</p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #ddd;">
                    <th>Subject code</th>
                    <th>Subject name</th>
                    <th>Room</th>
                    <th>Day</th>
                    <th>Start Time</th>
                    <th>End time</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $s)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $s->subject_id }}</td>
                            <td>{{ $s->subject_name ?? '' }}</td>
                            <td>{{ $s->subject_place ?? '' }}</td>
                            <td>{{ $s->subject_day ?? '' }}</td>
                            <td>{{ $s->subject_start_time ?? '' }}</td>
                            <td>{{ $s->subject_end_time ?? '' }}</td>
                        <td><button type="submit" form="add-form-add-sub" name="sub"
                            value="{{ $s->subject_id }}">Add</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div style="margin-top:12px;">
            {{ $subjects->links() }}
        </div>
    @endif
</div>
@endsection

<?php
//route('students.add-subject')
?>