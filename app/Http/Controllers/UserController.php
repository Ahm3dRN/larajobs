<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Show Register form
    public function create()
    {
        return view('users.register');
    }

    // Store New User
    public function store(Request $request)
    {
        $form_fields = $request->validate([
            'name' => ['required', 'min:3'],
            'email' => ['required', 'email', Rule::unique('users', 'email')],
            'password' => ['required', 'confirmed', 'min:6'],
        ]);
        $form_fields['password'] = bcrypt($form_fields['password']);
        // create user
        $user = User::create($form_fields);
        // login user
        auth()->login($user);
        return redirect('/')->with('message', 'User Created And Logged in.');
    }

    public function logout(Request $request)
    {
        auth()->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/')->with('message', "You Have Been Logged out!");
    }
    
    // Show login form
    public function login(Request $request)
    {
        return view('users.login');
    }

    // Authenticate user
    public function authenticate(Request $request)
    {
        $form_fields = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
        if(auth()->attempt($form_fields)) {
            $request->session()->regenerate();
            return redirect('/')->with('message', 'You are now logged in');
        }
        return back()->withErrors(['email' => "Invalid Credentials"])->onlyInput('email');

    }
}
