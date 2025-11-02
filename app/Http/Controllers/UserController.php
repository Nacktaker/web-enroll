<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;

class UserController extends SearchableController
{
    const MAX_ITEMS = 15;

    #[\Override]
    function getQuery(): Builder
    {
        return User::query()->orderBy('id', 'desc');
    }

    #[\Override]
    function applyWhereToFilterByTerm(Builder $query, string $word): void
    {
        $query->where('firstname', 'LIKE', "%{$word}%")
            ->orWhere('lastname', 'LIKE', "%{$word}%")
            ->orWhere('email', 'LIKE', "%{$word}%")
            ->orWhere('role', 'LIKE', "%{$word}%")
            ->orWhere('id', 'LIKE', "%{$word}%")
            ;
    }
    /**
     * Display a listing of users.
     */
    public function list(Request $request): View
    {
        Gate::authorize('adminMenu', Auth::user());
        $criteria = $this->prepareCriteria($request->query());
        $query = $this->search($criteria);

        $users = $query->paginate(self::MAX_ITEMS)->appends(['term' => $criteria['term']]);

        return view('users.list', compact('users', 'criteria'));
    }

    /**
     * Display a single user.
     */
    public function view($id): View
    {
        Gate::authorize('adminMenu', Auth::user());
        $user = User::findOrFail($id);

        return view('users.view', compact('user'));
    }

    /**
     * Show form to create a new user.
     */
    public function create(): View
    {
        Gate::authorize('adminMenu', Auth::user());
        return view('users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        Gate::authorize('adminMenu', Auth::user());
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
    'teacher_code' => 'required_if:role,TEACHER|nullable|string|max:50|unique:teacher,teacher_code', // บังคับถ้าเป็นอาจารย์
    'teacher_faculty' => 'required_if:role,TEACHER|nullable|string|max:255',
        ]);

        try {
            $user = new User();
            $user->firstname = $data['firstname'];
            $user->lastname = $data['lastname'];
            $user->email = $data['email'];
            $user->password = Hash::make($data['password']);
            $user->role = isset($data['role']) ? strtoupper($data['role']) : ($request->input('role') ? strtoupper($request->input('role')) : 'STUDENT');
            $user->save();

            // If the role indicates a student or teacher, create the linked record as well.
            $role = $user->role ?? 'STUDENT';
            if ($role === 'STUDENT') {
                Student::create([
                    'u_id' => $user->id,
                    'stu_code' => $data['stu_code'] ?? null,
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

            return redirect()->route('users.list')->with('status', 'User created successfully.');
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }

    /**
     * Show form to edit an existing user.
     */
    public function edit($id): View
    {
        Gate::authorize('adminMenu', Auth::user());
        $user = User::findOrFail($id);

        return view('users.update', compact('user'));
    }

    /**
     * Update an existing user.
     */
    public function update(Request $request, $id)
    {
        Gate::authorize('adminMenu', Auth::user());
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

        try {
            $user->save();
            return redirect()->route('users.view', $user->id)->with('status', 'User updated successfully.');
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {    
        Gate::authorize('adminMenu', Auth::user());
        try {
            $user = User::findOrFail($id);

            // Prevent deleting the currently authenticated user
            if (Auth::check() && Auth::id() === $user->id) {
                return redirect()->route('users.view', $user->id)->with('status', 'You cannot delete your own account.');
            }

            $user->delete();
            return redirect()->route('users.list')->with('status', 'User deleted.');
        } catch (QueryException $excp) {
            return redirect()->back()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }

        /**
     * แสดงข้อมูลของผู้ใช้ที่ล็อกอินอยู่ (โปรไฟล์ของตัวเอง)
     */
    public function selfview(): View
    {
        $user = Auth::user();


        return view('users.self-view', compact('user'));
    }

    /**
     * แสดงฟอร์มอัปเดตข้อมูลของผู้ใช้ที่ล็อกอินอยู่
     */
    public function showSelfUpdateForm(): View
    {
        $user = Auth::user();
        
        return view('users.self-update', compact('user'));
    }

    /**
     * อัปเดตข้อมูลของผู้ใช้ที่ล็อกอินอยู่
     */
    public function selfUpdate(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in first.');
        }

        $data = $request->validate([
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->firstname = $data['firstname'];
        $user->lastname = $data['lastname'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        try {
            $user->save();
            return redirect()->route('self.view')->with('status', 'Profile updated successfully.');
        } catch (QueryException $excp) {
            return redirect()->back()->withInput()->withErrors([
                'error' => $excp->errorInfo[2] ?? $excp->getMessage(),
            ]);
        }
    }

}
            
    
