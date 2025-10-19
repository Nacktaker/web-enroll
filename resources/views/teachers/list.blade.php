@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Teachers</h1>
    <p><a href="{{ route('teachers.create') }}">Add Teacher</a></p>

    @if($teachers->isEmpty())
        <p>No teachers found.</p>
    @else
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="text-align:left; border-bottom:1px solid #ddd;">
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($teachers as $t)
                    <tr style="border-bottom:1px solid #f1f1f1;">
                        <td>{{ $t->id }}</td>
                        <td>{{ $t->name }}</td>
                        <td>{{ $t->email }}</td>
                        <td><a href="{{ route('teachers.show', $t->id) }}">View</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
