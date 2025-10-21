<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Student;

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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email',
        ]);

        Student::create($data);

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:students,email,' . $student->id,
        ]);

        $student->update($data);

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
