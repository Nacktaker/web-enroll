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
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;

class StudentController extends SearchableController
{
    const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        // Base query for searching students
        return Student::query()->with('user')->orderBy('id', 'desc');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        // Search student fields and related user fields
        $query->where('stu_code', 'LIKE', "%{$word}%")
            ->orWhere('faculty', 'LIKE', "%{$word}%")
            ->orWhere('department', 'LIKE', "%{$word}%")
            ->orWhereHas('user', function ($q) use ($word) {
                $q->where('firstname', 'LIKE', "%{$word}%")
                  ->orWhere('lastname', 'LIKE', "%{$word}%")
                  ->orWhere('email', 'LIKE', "%{$word}%");
            });
    }
    public function index(Request $request): View
    {
        Gate::authorize('adminMenu', Auth::user());
        session()->put('bookmarks.students.list', request()->fullUrl());

        $criteria = $this->prepareCriteria($request->query());
        $query = $this->search($criteria);

        $students = $query->paginate(self::MAX_ITEMS)->appends(['term' => $criteria['term']]);

        return view('students.list', compact('students', 'criteria'));
    }

    public function show($id): View
    {
        Gate::authorize('adminMenu', Auth::user());
        $student = Student::findOrFail($id);

        return view('students.view', compact('student'));
    }

    public function create(): View
    {
        Gate::authorize('adminMenu', Auth::user());
        return view('students.add');
    }

    public function store(Request $request)
    {
        Gate::authorize('adminMenu', Auth::user());
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

        try {
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

            Student::create(array_filter($stuData, function ($v) {
                return $v !== null;
            }));

            return redirect()->route('students.index')->with('status', 'Student created');
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }

    public function edit($id): View
    {
        Gate::authorize('adminMenu', Auth::user());
        $student = Student::findOrFail($id);

        return view('students.update', compact('student'));
    }

    public function update(Request $request, $id)
    {
        Gate::authorize('adminMenu', Auth::user());
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

        try {
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
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified student.
     */
    public function destroy($u_id)
    {
        Gate::authorize('adminMenu', Auth::user());
       try {
           $student = Student::find($u_id);
           $user = User::where('id', $student->u_id)->first();

           $student->delete();
           if ($user) {
               $user->delete();
           }

           return redirect()->route('students.list')->with('status', 'Student deleted');
       } catch (QueryException $excp) {
           return redirect()->back()->withErrors([
               'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
           ]);
       }
}

    public function showaddsubform(
        Request $request,
        SubjectController $subjectcontroller,
        $id
    ): View {
        // allow admin or the student themselves
        try {
            Gate::authorize('adminMenu', Auth::user());
        } catch (\Exception $e) {
            Gate::authorize('studentMenu', Auth::user());
        }
    // Load the student by the authenticated user's id (u_id) or by provided user id.
    // Many places in the views pass the Auth::user()->id as the {id} route param,
    // so treat the incoming $id as a user id (u_id) and resolve the Student record.
    $students = Student::where('u_id', $id)->firstOrFail();
        $subjects = Subject::orderBy('id', 'desc')->get();
        $query = $subjectcontroller
            ->getQuery()
            ->whereDoesntHave(
                'students',
                function (Builder $innerQuery) use ($students) {
                        // The pivot stores the student's code in 'stu_id' and the Student model's
                        // column is 'stu_code'. When filtering the related Student query, match
                        // against the student's 'stu_code'.
                        return $innerQuery->where('stu_code', $students->stu_code);
                    },
            );
    // Use Laravel Request->query() to get query parameters (getQueryParams() is PSR-7 and not available on Illuminate\Http\Request)
    $criteria = $subjectcontroller->prepareCriteria($request->query());
        $query = $subjectcontroller
            ->filter($query, $criteria)
            ->withCount('students');

        return view('students.add-subject-form',
        [ 'criteria' => $criteria,
            'student' => $students,
            'subjects' => $query->paginate($subjectcontroller::MAX_ITEMS),]
        , compact('subjects', 'students'));
    }

    public function addsub(Request $request, $id)
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
            return redirect()->back()->with('status1', 'คุณได้ส่งคำขอลงทะเบียนวิชานี้แล้ว กรุณารอการอนุมัติ');
        }

        try {
            // สร้างคำขอลงทะเบียนใหม่
            Pendingregister::create([
                'stu_id' => $student->stu_code,
                'subject_id' => $subject->subject_id,
            ]);

            return redirect()->back()->with('status', 'ส่งคำขอลงทะเบียนเรียบร้อยแล้ว กรุณารอการอนุมัติ');
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }
    public function adddrop(Request $request, $id)
    {
        $student = Student::where('u_id', $id)->first();
        $data = $request->all();
        $stusubject = StudentSubject::query()
            ->where('subject_id', $data['sub'])
            ->firstOrFail();
        try {
            Pendingwithdraw::create([
                'stu_id' => $student->stu_code, // ใช้ $student->code ตามตรรกะเดิมของคุณ
                'subject_id' => $stusubject->subject_id     // ใช้ id (Primary Key) ของ subject ที่หาเจอ
            ]);

            $stusubject->delete();

            return redirect()->back()->with('status', 'รายวิชาถูกส่งคำร้องขอถอนแล้ว กรุณารอการอนุมัติ');
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }


    public function schedule(Request $request, SubjectController $subjectcontroller, $id): view
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


        return view('students.schedule', compact('pensubjects', 'students', 'studentsubjects'));
    }

    public function showschedule(Request $request, SubjectController $subjectcontroller, $id): view
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

        return view('students.show-schedule', compact('pensubjects', 'students', 'studentsubjects'));
    }




    public function removewaiting(Request $request, $id)
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
