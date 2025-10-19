<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Teacher;

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
        ]);

        Teacher::create($data);

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email,' . $teacher->id,
        ]);

        $teacher->update($data);

        return redirect()->route('teachers.show', $teacher->id)->with('status', 'Teacher updated');
    }
}
