<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): View
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('users.list', compact('users'));
    }

    /**
     * Display a single user.
     */
    public function show($id): View
    {
        $user = User::findOrFail($id);

        return view('users.view', compact('user'));
    }
}
            
    
