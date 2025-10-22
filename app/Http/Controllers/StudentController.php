<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Student;
use App\Models\User;

class StudentController extends Controller
{
    public function index(): View
    {
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
}
