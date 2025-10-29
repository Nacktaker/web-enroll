@extends('layouts.main')

@section('title', 'ตั้งค่าช่วงเวลาลงทะเบียน')

@section('content')
<div class="container">
    <h2>ตั้งค่าช่วงเวลาลงทะเบียน</h2>

    @if(session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.enrollment.update') }}">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="start_date">วันที่เริ่มลงทะเบียน</label>
            <input type="date" name="start_date" id="start_date" value="{{ $startDate }}" required>
        </div>

        <div class="form-group">
            <label for="end_date">วันที่สิ้นสุดการลงทะเบียน</label>
            <input type="date" name="end_date" id="end_date" value="{{ $endDate }}" required>
        </div>

        <div class="form-buttons">
            <button type="submit" class="btn btn-primary">บันทึก</button>
            <a href="{{ URL('/home') }}" class="btn btn-secondary">ยกเลิก</a>
        </div>
    </form>
</div>
@endsection