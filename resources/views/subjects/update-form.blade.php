@extends('subjects.main', [
    'title' => $subject->subject_name,
])

@section('content')
    <form action="{{ route('subjects.update', [
        'subject' => $subject->subject_id,
    ]) }}" method="post">
        @csrf

        <div class="app-cmp-form-detail">
            <label for="app-inp-code">Code</label>
            <input type="text" id="app-inp-code" name="code" value="{{ $product->subject_id }}" required />

            <label for="app-inp-name">Name</label>
            <input type="text" id="app-inp-name" name="name" value="{{ $product->subject_name }}" required />

            <label for="app-inp-price">Room</label>
            <input type="number" id="app-inp-price" name="price" value="{{ $product->subject_place }}" step="any" required />
        </div>

        <div class="app-cmp-form-actions">
            <button type="submit">Update</button>
        </div>
    </form>
@endsection