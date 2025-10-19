<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Psr\Http\Message\ServerRequestInterface;

class LoginController extends Controller
{
    function showLoginForm(): View
    {
        return view('logins.login');
    }

    function showRegisterForm(): View
    {
        return view('logins.register');
    }

    function authenticate(ServerRequestInterface $request): RedirectResponse
    {
        // Get credentials from user
        $validator = Validator::make(
            $request->getParsedBody(),
            [
                'email' => 'required',
                'password' => 'required',
            ],
        );

        // Authenticate by using method attempt()
        if (
            $validator->passes() &&
            Auth::attempt(
                $validator->safe()->only(['email', 'password']),
            )
        ) {
            // Regenerate the new session ID
            session()->regenerate();

            // Redirect to the requested URL or
            // to route products.list if does not specify
            return redirect()->intended(route('products.list'));
        }

        // If cannot authenticate redirect back to loginForm with error message
        $validator
            ->errors()
            ->add(
                'credentials',
                'The provided credentials do not match our records.',
            );

        return redirect()
            ->back()
            ->withErrors($validator);
    }

    /**
     * Handle registration request.
     */
    function register(Request $request): RedirectResponse
    {
        $data = $request->only(['name', 'email', 'password', 'password_confirmation']);

        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = new User();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->save();

        // Log the user in
        Auth::login($user);

        return redirect()->intended(route('products.list'));
    }

    function logout(): RedirectResponse
    {
        Auth::logout();
        session()->invalidate();
        
        // Regenerate CSRF token
        session()->regenerateToken();

        return redirect()->route('login');
    }
}