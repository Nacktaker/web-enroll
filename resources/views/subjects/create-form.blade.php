@extends('subjects.main', [
    'title' => 'Create',
])

@section('content')
    <form action="{{ route('subjects.create') }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Subject ID</label>
            <input type="text" id="app-inp-code" name="subject_id" required />

            <label for="app-inp-name">Subject Name</label>
            <input type="text" id="app-inp-name" name="subject_name" required />

            <label for="app-inp-price">Subject Place</label>
            <input type="text" id="app-inp-price" name="subject_place" step="any" required />

            <label for="app-inp-price">Subject Time</label>
            <input type="text" id="app-inp-price" name="subject_time" step="any" required />

            


        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Create</button>
        </div>
    </form>
@endsection