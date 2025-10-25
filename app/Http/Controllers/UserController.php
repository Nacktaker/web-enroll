<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function list(Request $request): View
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('users.list', compact('users'));
    }

    /**
     * Display a single user.
     */
    public function view($id): View
    {
        $user = User::findOrFail($id);

        return view('users.view', compact('user'));
    }

    /**
     * Show form to create a new user.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'nullable|string',
            'stu_code' => 'required_if:role,STUDENT|nullable|string|max:50|unique:student,stu_code', // บังคับถ้าเป็นนักเรียน, และห้ามซ้ำในตาราง students
    'faculty' => 'required_if:role,STUDENT|nullable|string|max:255', // บังคับถ้าเป็นนักเรียน
    'department' => 'nullable|string|max:255', // ไม่บังคับ
    'year' => 'nullable|integer|min:1|max:8', // ไม่บังคับ (ถ้ากรอกต้องเป็นเลข 1-8)

    // --- ส่วนของ Teacher (บังคับกรอกเมื่อ role=TEACHER) ---
    'teacher_code' => 'required_if:role,TEACHER|nullable|string|max:50|unique:teachers,teacher_code', // บังคับถ้าเป็นอาจารย์
    'teacher_faculty' => 'required_if:role,TEACHER|nullable|string|max:255',
        ]);

        $user = new User();
        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->role = isset($data['role']) ? strtoupper($data['role']) : ($request->input('role') ? strtoupper($request->input('role')) : 'STUDENT');
        $user->save();

        // If the role indicates a student or teacher, create the linked record as well.
        $role = $user->role ?? 'STUDENT';
        try {
            if ($role === 'STUDENT') {
        Student::create([
            'u_id' => $user->id,
            'stu_code' => $data['stu_code'] ?? null, // ดึงจาก $data ที่ validate แล้ว
            'faculty' => $data['faculty'] ?? null,
            'department' => $data['department'] ?? null,
            'year' => $data['year'] ?? null,
        ]);
    } elseif ($role === 'TEACHER') {
        Teacher::create([
            'u_id' => $user->id,
            'teacher_code' => $data['teacher_code'] ?? null,
            'faculty' => $data['teacher_faculty'] ?? $data['faculty'] ?? null,
        ]);
    }
        } catch (\Throwable $e) {
            // Don't fail user creation if related table/model is missing; log for debugging.
            logger()->error('Failed to create related student/teacher record: ' . $e->getMessage());
       }

        return redirect()->route('users.list')->with('status', 'User created successfully.');
    }

    /**
     * Show form to edit an existing user.
     */
    public function edit($id): View
    {
        $user = User::findOrFail($id);

        return view('users.update', compact('user'));
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|string',
        ]);

        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        if (!empty($data['role'])) {
            $user->role = strtoupper($data['role']);
        }

        $user->save();

        return redirect()->route('users.view', $user->id)->with('status', 'User updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the currently authenticated user
        if (Auth::check() && Auth::id() === $user->id) {
            return redirect()->route('users.view', $user->id)->with('status', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users.list')->with('status', 'User deleted.');
    }
}
            
    
