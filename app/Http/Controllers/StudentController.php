<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\User;
use App\Models\Subject;
use App\Models\Pendingregister;
use App\Models\Pendingwithdraw;
use App\Models\Studentsubject;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class StudentController extends Controller
{
    public function index(): View
    {
        session()->put('bookmarks.students.list', request()->fullUrl());
        
        $students = Student::orderBy('id', 'desc')->get();

        return view('students.list', compact('students'));
    }

    public function show($id): View
    {
        $student = Student::findOrFail($id);

        return view('students.view', compact('student'));
    }

    public function create(): View
    {
        return view('students.add');
    }

    public function store(Request $request)
    {
        // Validate incoming student + user fields
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'stu_code' => 'nullable|string|max:64',
            'faculty' => 'nullable|string|max:64',
            'department' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:4',
        ]);

        // Create a linked User first
        $user = User::create([
            'firstname' => $data['firstname'],
            'lastname' => $data['lastname'],
            'email' => $data['email'],
            // generate a random password if none provided (admins can reset later)
            'password' => bcrypt(str()->random(12)),
            'role' => 'STUDENT',
        ]);

        // Create the student record and link to user
        $stuData = [
            'u_id' => $user->id,
            'stu_code' => $data['stu_code'] ?? null,
            'faculty' => $data['faculty'] ?? null,
            'department' => $data['department'] ?? null,
            'year' => $data['year'] ?? null,
        ];

        Student::create(array_filter($stuData, function ($v) { return $v !== null; }));

        return redirect()->route('students.index')->with('status', 'Student created');
    }

    public function edit($id): View
    {
        $student = Student::findOrFail($id);

        return view('students.update', compact('student'));
    }

    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);

        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . ($student->u_id ?? 'NULL'),
            'stu_code' => 'nullable|string|max:64',
            'faculty' => 'nullable|string|max:64',
            'department' => 'nullable|string|max:255',
            'year' => 'nullable|string|max:4',
        ]);

        // Update linked user if present
        if ($student->u_id) {
            $user = User::find($student->u_id);
            if ($user) {
                $user->firstname = $data['firstname'];
                $user->lastname = $data['lastname'];
                $user->email = $data['email'];
                $user->save();
            }
        }

        $student->update([
            'stu_code' => $data['stu_code'] ?? $student->stu_code,
            'faculty' => $data['faculty'] ?? $student->faculty,
            'department' => $data['department'] ?? $student->department,
            'year' => $data['year'] ?? $student->year,
        ]);

        return redirect()->route('students.show', $student->id)->with('status', 'Student updated');
    }

    /**
     * Remove the specified student.
     */
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return redirect()->route('students.index')->with('status', 'Student deleted');
    }
        
    public function showaddsubform(Request $request , SubjectController $subjectcontroller , $id) : view
    {
        $students = Student::find($id);
        $subjects = Subject::orderBy('id', 'desc')->get();

        return view('students.add-subject-form', compact('subjects','students'));   
    }

    public function addsub(Request $request , $id) 
    {
        $student = Student::where('u_id', $id)->firstOrFail();
        $data = $request->all();
        $subject = Subject::query()
            ->where('subject_id', $data['sub'])
            ->firstOrFail();

        // ตรวจสอบว่าผู้เรียนลงทะเบียนรายวิชานี้แล้วหรือยัง (ป้องกันซ้ำ)
        $already = Studentsubject::where('stu_id', $student->stu_code)
                    ->where('subject_id', $subject->subject_id)
                    ->exists();

        if ($already) {
            return redirect()->back()->with('status', 'คุณได้ลงทะเบียนวิชานี้แล้ว');
        }

        // ตรวจสอบว่ามีคำขอลงทะเบียนที่รออนุมัติอยู่แล้วหรือไม่
        $pendingExists = Pendingregister::where('stu_id', $student->stu_code)
                    ->where('subject_id', $subject->subject_id)
                    ->exists();

        if ($pendingExists) {
            return redirect()->back()->with('status', 'คุณได้ส่งคำขอลงทะเบียนวิชานี้แล้ว กรุณารอการอนุมัติ');
        }

        // สร้างคำขอลงทะเบียนใหม่
        Pendingregister::create([
            'stu_id' => $student->stu_code,
            'subject_id' => $subject->subject_id,
        ]);

        return redirect()->back()->with('status', 'ส่งคำขอลงทะเบียนเรียบร้อยแล้ว กรุณารอการอนุมัติ');
    }
    public function adddrop(Request $request , $id) 
    {
        $student = Student::where('u_id', $id)->first();
        $data = $request->all();
        $stusubject = StudentSubject::query()
            ->where('subject_id', $data['sub'])
            ->firstOrFail();
        Pendingwithdraw::create([
        'stu_id' => $student->stu_code, // ใช้ $student->code ตามตรรกะเดิมของคุณ
        'subject_id' => $stusubject->subject_id     // ใช้ id (Primary Key) ของ subject ที่หาเจอ
    ]);

    $stusubject->delete();

    return redirect()->back()->with('status', 'รายวิชาถูกส่งคำร้องขอถอนแล้ว กรุณารอการอนุมัติ');
    }


    public function schedule(Request $request , SubjectController $subjectcontroller , $id) : view
    {
        $students = Student::where('u_id', $id)->first();
        $pensubjects = PendingRegister::where('stu_id', $students->stu_code)
                                    ->with('subject') 
                                    ->orderBy('id', 'desc')
                                    ->get();

        $studentsubjects = Studentsubject::where('stu_id', $students->stu_code)
                                    ->with('subject') 
                                    ->orderBy('id', 'desc')
                                    ->get();


        return view('students.schedule', compact('pensubjects','students' ,'studentsubjects' ));   
    }

    public function showschedule(Request $request , SubjectController $subjectcontroller , $id) : view
    {
        $students = Student::findOrFail($id);
        
        if (!$students) {
            abort(404, 'Student not found');
        }

        $pensubjects = PendingRegister::where('stu_id', $students->stu_code)
                                    ->with('subject') 
                                    ->orderBy('id', 'desc')
                                    ->get();

        $studentsubjects = Studentsubject::where('stu_id', $students->stu_code)
                                    ->with('subject') 
                                    ->orderBy('id', 'desc')
                                    ->get();

        return view('students.show-schedule', compact('pensubjects','students' ,'studentsubjects' ));   
    }
    


   
    public function removewaiting(Request $request , $id) 
    {
        $data = $request->all();
        $sid = $data['sub'];
        $pen = Pendingregister::findorfail($sid);
        $pen->delete();
        return redirect()->back()->with('status', 'ยกเลิกคำร้องขอลงทะเบียนเรียบร้อยแล้ว');
    }

    public function viewSubjects($id): View
    {
        // Find student and eager load user info
        $student = Student::with('user')->findOrFail($id);
        
        // Get enrolled subjects with their info and teacher info
        $enrolledSubjects = Studentsubject::where('stu_id', $student->stu_code)
            ->with(['subject.teacher.user'])
            ->orderBy('subject_id')
            ->get();

        return view('students.view-subjects', [
            'student' => $student,
            'enrolledSubjects' => $enrolledSubjects
        ]);
    }
}