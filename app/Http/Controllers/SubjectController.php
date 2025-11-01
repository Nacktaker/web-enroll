<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class SubjectController extends SearchableController
{
    // แสดงรายชื่อวิชา
    const MAX_ITEMS = 5;

    #[\Override]
    function getQuery(): Builder
    {
        return Subject::orderBy('subject_id');
    }
    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        parent::applyWhereToFilterByTerm($query, $word);

        $query->orWhere('subject_id', 'LIKE', "%{$word}%");
    }
    // แสดงรายชื่อวิชา
    public function list(Request $request): View
    {
        $criteria = $this->prepareCriteria($request->query());
        $query = $this->search($criteria);
        $subjects = Subject::with('teacher.user')
            ->orderBy('subject_id')
            ->get();


        return view('subjects.list', [
            'criteria' => $criteria,
            'subjects' => $query->paginate(self::MAX_ITEMS),
        ]);
    }
    // แสดงรายละเอียดวิชา
    public function view(string $subject): View
    {
        $subject = Subject::where('subject_id', $subject)->firstOrFail();

        return view('subjects.view', [
            'subject' => $subject,
        ]);
    }

    /**
     * Show form to create a subject. Admins may choose teacher; teachers will create for themselves.
     */
    public function create(): View
    {
        $user = Auth::user();

        // Only teachers and admins may access
        if (!($user && (strtolower($user->role) === 'teacher' || strtolower($user->role) === 'admin'))) {
            abort(403);
        }

        $teachers = [];
        // If admin, provide teacher list for selection
        if (strtolower($user->role) === 'admin') {
            $teachers = Teacher::with('user')->get();
        }

        $teacher_code = null;
        if (strtolower($user->role) === 'teacher') {
            $t = Teacher::where('u_id', $user->id)->first();
            $teacher_code = $t->teacher_code ?? null;
        }

        return view('subjects.create', [
            'teachers' => $teachers,
            'user' => $user,
            'teacher_code' => $teacher_code,
        ]);
    }

    /**
     * Store a new subject. Admin must provide teacher_code; teacher's submissions are tied to their own teacher_code.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if (!($user && (strtolower($user->role) === 'teacher' || strtolower($user->role) === 'admin'))) {
            abort(403);
        }

        $rules = [
            'subject_id' => ['required', 'string', 'max:50', Rule::unique('subjects', 'subject_id')],
            'subject_name' => ['required', 'string', 'max:255'],
            'subject_description' => ['nullable', 'string'],
            'subject_place' => ['nullable', 'string', 'max:255'],
            'subject_day' => ['nullable', 'string', 'max:50'],
            'subject_start_time' => ['nullable', 'date_format:H:i'],
            'subject_end_time' => ['nullable', 'date_format:H:i'],
        ];

        if (strtolower($user->role) === 'admin') {
            $rules['teacher_code'] = ['required', 'string', 'exists:teacher,teacher_code'];
        }

        $data = $request->validate($rules);

        // If teacher, find their teacher_code
        if (!isset($data['teacher_code'])) {
            $teacher = Teacher::where('u_id', $user->id)->first();
            if (!$teacher) {
                return redirect()->back()->withErrors(['teacher' => 'Cannot find teacher record for your account.']);
            }
            $data['teacher_code'] = $teacher->teacher_code;
        }

        Subject::create([
            'subject_id' => $data['subject_id'],
            'subject_name' => $data['subject_name'],
            'subject_description' => $data['subject_description'] ?? null,
            'subject_place' => $data['subject_place'] ?? null,
            'subject_day' => $data['subject_day'] ?? null,
            'subject_start_time' => $data['subject_start_time'] ?? null,
            'subject_end_time' => $data['subject_end_time'] ?? null,
            'teacher_code' => $data['teacher_code'],
        ]);

        return redirect()->route('subjects.list')->with('status', 'Subject created successfully.');
    }

    /**
     * Show students enrolled in a subject. Accessible by teachers (for their own subjects) and admins.
     */
    public function students(string $subject): View
    {
        $user = Auth::user();

        $subjectModel = Subject::where('subject_id', $subject)->firstOrFail();

        // Authorization: teacher may only view their own subjects
        if ($user && strtolower($user->role) === 'teacher') {
            $teacher = Teacher::where('u_id', $user->id)->first();
            if (!$teacher || ($subjectModel->teacher_code ?? null) !== ($teacher->teacher_code ?? null)) {
                abort(403);
            }
        }

        // Get students from studentsubject table where stu_id equals student.stu_code
        $studentCodes = \App\Models\Studentsubject::where('subject_id', $subjectModel->subject_id)
            ->pluck('stu_id')
            ->toArray();

        $students = \App\Models\Student::whereIn('stu_code', $studentCodes)
            ->with('user')
            ->get();

        return view('subjects.students', [
            'subject' => $subjectModel,
            'students' => $students,
        ]);
    }
}
