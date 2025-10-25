<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class LoginController extends Controller
{
    /**
     * Show the login form.
     */
    public function login(): View
    {
        return view('logins.login');
    }

    /**
     * Authenticate user using email and password.
     */
    public function authenticate(Request $request): RedirectResponse
    {
        $data = $request->only(['email', 'password']);

        $validator = Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Auto-create default admin if no users exist (optional)
        if (User::count() === 0) {
            User::create([
                'firstname' => 'Somchai',
                'lastname' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('123456'),
                'role' => 'admin',
            ]);
        }

        $user = User::where('email', $data['email'])->first();

        if (! $user) {
            return redirect()->back()->withErrors(['credentials' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'])->withInput();
        }

        // If password in DB is not a bcrypt/argon hash, compare plain text and rehash
        $dbPassword = $user->password;
        $isHashed = is_string($dbPassword) && preg_match('/^\$(2y|2b|argon2)/', $dbPassword);

        if ($isHashed) {
            $ok = Hash::check($data['password'], $dbPassword);
        } else {
            // Legacy plaintext stored — compare directly and rehash on success
            $ok = hash_equals($dbPassword, $data['password']);
            if ($ok) {
                $user->password = Hash::make($data['password']);
                $user->save();
            }
        }

        if (! $ok) {
            return redirect()->back()->withErrors(['credentials' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง'])->withInput();
        }

        // Log the user in
        Auth::login($user);
        session()->regenerate();

        return redirect('/home');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/home');
    }
}