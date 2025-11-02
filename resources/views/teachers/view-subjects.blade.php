@extends('layouts.main')

@section('title', 'รายวิชาที่สอน - ' . $teacher->user->firstname . ' ' . $teacher->user->lastname)

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="my-3">
                <h1 class="text-xl font-semibold">รายวิชาที่สอน</h1>
                <p class="text-gray-600">อาจารย์ {{ $teacher->user->firstname . ' ' . $teacher->user->lastname }}</p>
                <p class="text-gray-600">รหัสอาจารย์: {{ $teacher->teacher_code }}</p>
            </div>

            <div class="card">
                <div class="card-header">รายวิชาทั้งหมด</div>
                <div class="card-body">
                    @if($subjects->isEmpty())
                        <p class="text-gray-500 text-center">ยังไม่มีรายวิชาที่สอน</p>
                    @else
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>รหัสวิชา</th>
                                        <th>ชื่อวิชา</th>
                                        <th>หน่วยกิต</th>
                                        <th>จำนวนนักศึกษา</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($subjects as $subject)
                                        <tr>
                                            <td>{{ $subject->subject_id }}</td>
                                            <td>{{ $subject->subject_name }}</td>
                                            <td>{{ $subject->credit }}</td>
                                            <td>{{ $subject->studentsubjects_count ?? 0 }}</td>
                                           
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="mt-3">
                <a href="{{ session()->get('bookmarks.view.subjects', route('teachers.show', $teacher->id)) }}" class="btn btn-secondary">
                    กลับ
                </a>
            </div>
        </div>
    </div>
</div>
@endsection