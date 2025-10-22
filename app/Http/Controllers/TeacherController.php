<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Teacher;
use App\Models\User;

class TeacherController extends Controller
{
    public function index(): View
    {
        $teachers = Teacher::orderBy('id', 'desc')->get();

        return view('teachers.list', compact('teachers'));
    }

    public function show($id): View
    {
        $teacher = Teacher::findOrFail($id);

        return view('teachers.view', compact('teacher'));
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
        ], function ($v) { return $v !== null; }));

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

        return redirect()->route('teachers.index')->with('status', 'Teacher deleted');
    }

    
}
    