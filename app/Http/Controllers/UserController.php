<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
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
        return view('users.adduser');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

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
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
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
            
    
