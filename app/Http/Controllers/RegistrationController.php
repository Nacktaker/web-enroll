<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;

class RegistrationController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->only([
            'firstname', 'lastname', 'email', 'password', 'role',
            'stu_code', 'faculty', 'department', 'year',
            'teacher_code', 'teacher_faculty'
        ]);

        $rules = [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:student,teacher,admin',
        ];

        if ($request->role === 'student') {
            $rules = array_merge($rules, [
                'stu_code' => 'required|string|max:255',
                'faculty' => 'required|string|max:255',
                'department' => 'required|string|max:255',
                'year' => 'required|string|max:10',
            ]);
        }

        if ($request->role === 'teacher') {
            $rules = array_merge($rules, [
                'teacher_code' => 'required|string|max:255',
                'teacher_faculty' => 'required|string|max:255',
            ]);
        }

        if ($request->role === 'admin') {
            $rules = array_merge($rules, [
                'admin_code' => 'required|string|max:255',
                'admin_unit' => 'required|string|max:255',
            ]);
        }

        $validator = validator($data, $rules);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        try {
            $user = new User();
            // Map firstname/lastname to fields in users table
            $user->firstname = $data['firstname'];
            $user->lastname = $data['lastname'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->role = strtoupper($data['role']);
            $user->save();

            if ($data['role'] === 'student') {
                $student = new Student();
                $student->u_id = $user->id;
                $student->stu_code = $data['stu_code'];
                $student->faculty = $data['faculty'];
                $student->department = $data['department'];
                $student->year = $data['year'];
                $student->save();
            }

            if ($data['role'] === 'teacher') {
                $teacher = new Teacher();
                $teacher->u_id = $user->id;
                $teacher->teacher_code = $data['teacher_code'];
                $teacher->faculty = $data['teacher_faculty'];
                $teacher->save();
            }

            DB::commit();

            return redirect('/create')->with('status', 'บัญชีถูกสร้างเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
