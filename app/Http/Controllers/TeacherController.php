<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Teacher;
use App\Models\User;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Pendingregister;
use App\Models\Studentsubject;
use App\Models\Pendingwithdraw;

class TeacherController extends SearchableController
{
    const MAX_ITEMS = 10;

    #[\Override]
    function getQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return Teacher::query()->with('user')->orderBy('id', 'desc');
    }

    #[\Override]
    function applyWhereToFilterByTerm(\Illuminate\Database\Eloquent\Builder $query, string $word): void
    {
        // Search teacher fields and related user (firstname/lastname/email)
        $query->where('teacher_code', 'LIKE', "%{$word}%")
            ->orWhere('faculty', 'LIKE', "%{$word}%")
            ->orWhereHas('user', function ($q) use ($word) {
                $q->where('firstname', 'LIKE', "%{$word}%")
                  ->orWhere('lastname', 'LIKE', "%{$word}%")
                  ->orWhere('email', 'LIKE', "%{$word}%");
            });
    }
    public function index(Request $request): View
    {
        session()->put('bookmarks.teachers.list', request()->fullUrl());

        $criteria = $this->prepareCriteria($request->query());
        $query = $this->search($criteria);

        $teachers = $query->paginate(self::MAX_ITEMS)->appends(['term' => $criteria['term']]);

        return view('teachers.list', compact('teachers', 'criteria'));
    }

    public function show($id): View
    {
        $teacher = Teacher::findOrFail($id);

        return view('teachers.view', compact('teacher'));
    }

    public function viewSubjects($id): View
    {
        // Find teacher and eager load user info
        $teacher = Teacher::with('user')->findOrFail($id);
        
        // Get subjects taught by this teacher
        $subjects = Subject::where('teacher_code', $teacher->teacher_code)
            ->orderBy('subject_id')
            ->get();

        return view('teachers.view-subjects', [
            'teacher' => $teacher,
            'subjects' => $subjects
        ]);
    }

    public function create(): View
    {
        return view('teachers.add');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'teacher_code' => 'nullable|string|max:64',
            'faculty' => 'nullable|string|max:64',
        ]);

        // Create linked user
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            'password' => bcrypt(str()->random(12)),
            'role' => 'TEACHER',
        ]);

        // Create teacher record
        Teacher::create(array_filter([
            'u_id' => $user->id,
            'teacher_code' => $data['teacher_code'] ?? null,
            'faculty' => $data['faculty'] ?? null,
        ], function ($v) {
            return $v !== null;
        }));

        return redirect()->route('teachers.index')->with('status', 'Teacher created');
    }

    public function edit($id): View
    {
        $teacher = Teacher::findOrFail($id);

        return view('teachers.update', compact('teacher'));
    }

    public function update(Request $request, $id)
    {
        $teacher = Teacher::findOrFail($id);

        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($teacher->u_id ?? 'NULL'),
            'teacher_code' => 'nullable|string|max:64',
            'faculty' => 'nullable|string|max:64',
        ]);

        // Update linked user
        if ($teacher->u_id) {
            $user = User::find($teacher->u_id);
            if ($user) {
                $user->firstname = $data['firstname'];
                $user->lastname = $data['lastname'];
                $user->email = $data['email'];
                $user->save();
            }
        }

        $teacher->update([
            'teacher_code' => $data['teacher_code'] ?? $teacher->teacher_code,
            'faculty' => $data['faculty'] ?? $teacher->faculty,
        ]);

        return redirect()->route('teachers.show', $teacher->id)->with('status', 'Teacher updated');
    }



    /**
     * Remove the specified teacher.
     */
    public function destroy($id)
    {
        $teacher = Teacher::findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.list')->with('status', 'Teacher deleted');
    }
    public function showapproveform(Request $request)
{
    $authUser = Auth::user();
    $teacher = Teacher::where('u_id', $authUser->id)->first();

    if (!$teacher) {
        dd('ไม่เจอ teacher', $authUser);
    }

    $sub = Subject::where('teacher_code', $teacher->teacher_code)->get();

    if ($sub->isEmpty()) {
        dd('ไม่เจอ subject ของครู', $teacher->teacher_code);
    }

    // ดึงข้อมูลการลงทะเบียนรอพร้อมข้อมูลนักศึกษาและวิชา
    $pen = Pendingregister::whereIn('subject_id', $sub->pluck('subject_id'))
        ->select('pendingregisters.*')
        ->join('student', 'pendingregisters.stu_id', '=', 'student.stu_code')
        ->join('users', 'student.u_id', '=', 'users.id')
        ->with(['subject', 'student.user'])
        ->get();

    

    return view('teachers.add-approve-form', compact('teacher', 'pen'));
}


    public function addapprove(Request $request, $id)
{
    // เช็คว่ามีค่า 'sub' หรือไม่
    if (!$request->has('sub')) {
        return redirect()->back()->withErrors('Missing sub parameter');
    }
    $data = $request->all();
    $pending = Pendingregister::where('id',$data['sub'])->firstOrFail();

    // สร้างข้อมูลใหม่
    StudentSubject::create([
        'stu_id' => $pending->stu_id,
        'subject_id' => $pending->subject_id,
    ]);

    // ลบข้อมูลเดิม
    $pending->delete();

    return redirect()->back()->with('status', 'Add Success');
}

public function showdropform(Request $request)
{
$authUser = Auth::user();
$teacher = Teacher::where('u_id', $authUser->id)->first();

if (!$teacher) {
    dd('ไม่เจอ teacher', $authUser);
}

$sub = Subject::where('teacher_code', $teacher->teacher_code)->get();

if ($sub->isEmpty()) {
    dd('ไม่เจอ subject ของครู', $teacher->teacher_code);
}

// ดึงข้อมูลการถอนรอพร้อมข้อมูลนักศึกษาและวิชา
$pen = Pendingwithdraw::whereIn('subject_id', $sub->pluck('subject_id'))
    ->with(['subject', 'student.user'])
    ->get();

return view('teachers.drop-approve-form', compact('teacher', 'pen'));
}


public function drop(Request $request, $id)
{
// เช็คว่ามีค่า 'sub' หรือไม่
if (!$request->has('sub')) {
    return redirect()->back()->withErrors('Missing sub parameter');
}
$data = $request->all();
$pending = Pendingwithdraw::where('id',$data['sub'])->firstOrFail();


// ลบข้อมูลเดิม
$pending->delete();

return redirect()->back()->with('status', 'Add Success');
}
}
