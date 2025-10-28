@extends('layouts.main')

@section('content')
<div class="container">@auth
    <form action="{{ route('teachers.add-drop', ['id' => \Auth::user()->id]) }}"id="add-form-drop-sub" method="post">@csrf</form>

    @endauth

    @if($pen->isEmpty())
        <p>No Enrollment found.</p>
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
                @foreach($pen as $s)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $s->subject_id }}</td>
                            <td>{{ $s->subject->subject_name ?? '' }}</td>
                            <td>{{ $s->subject->subject_place ?? '' }}</td>
                            <td>{{ $s->subject->subject_day ?? '' }}</td>
                            <td>{{ $s->subject->subject_start_time ?? '' }}</td>
                            <td>{{ $s->subject->subject_end_time ?? '' }}</td>
                        <td><button type="submit" form="add-form-drop-sub" name="sub"
                            value="{{ $s->id }}">Confirm</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
       