@extends('layouts.main')
@section('content')
<div class="container">
    <h2>Pending Subject Withdrawal Approvals</h2>

    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pen as $pending)
                    <tr>
                        <td>
                            @if($pending->student && $pending->student->user)
                                {{ $pending->student->user->firstname }} {{ $pending->student->user->lastname }}
                                <br>
                                <small>{{ $pending->student->stu_code }}</small>
                            @else
                                <span class="text-muted">Unknown Student</span>
                            @endif
                        </td>
                        <td>
                            @if($pending->subject)
                                {{ $pending->subject->subject_name }}
                                <br>
                                <small>{{ $pending->subject->subject_id }}</small>
                            @else
                                <span class="text-muted">Unknown Subject</span>
                            @endif
                        </td>
                        <td>
                            @if($pending->subject && $pending->subject->teacher && $pending->subject->teacher->user)
                                {{ $pending->subject->teacher->user->firstname }} 
                                {{ $pending->subject->teacher->user->lastname }}
                                <br>
                                <small>{{ $pending->subject->teacher_code }}</small>
                            @else
                                <span class="text-muted">No Teacher Assigned</span>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.adddrop', $pending->id) }}" method="POST" class="d-inline">
                                @csrf
                                <input type="hidden" name="sub" value="{{ $pending->id }}">
                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                            </form>
                            
                            <form action="{{ route('admin.rejectdrop', $pending->id) }}" method="POST" class="d-inline ml-2">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="sub" value="{{ $pending->id }}">
                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                            </form>
                        </td>
                    </tr>
                @endforeach

                @if($pen->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">No pending withdrawals found.</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection