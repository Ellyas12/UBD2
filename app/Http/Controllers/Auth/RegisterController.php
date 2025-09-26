<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // ✅ Validation rules
        $request->validate([
            'username'   => 'required|unique:users,username|min:4|max:20',
            'email'      => 'required|email|unique:users,email',
            'nidn'       => 'required|numeric|unique:users,nidn',
            'password'   => [
                'required',
                'string',
                'min:8',
                'confirmed',       // requires password_confirmation
                'regex:/[A-Z]/',   // at least one uppercase
                'regex:/[0-9]/',   // at least one number
            ]
        ], [
            'username.required' => '*Username must be inputted.',
            'username.min'      => '*Username must be at least 4 characters.',
            'username.unique'   => '*This username is already taken.',
            'email.required'    => '*Email must be inputted.',
            'email.email'       => '*Enter a valid email address.',
            'email.unique'      => '*This email is already registered.',
            'nidn.required'     => '*NIDN must be inputted.',
            'nidn.numeric'      => '*NIDN must be a number.',
            'nidn.unique'       => '*This NIDN is already registered.',
            'password.required' => '*Password must be inputted.',
            'password.min'      => '*Password must be at least 8 characters.',
            'password.confirmed'=> '*Password confirmation does not match.',
            'password.regex'    => '*Password must contain at least one uppercase letter and one number.',
        ]);

        // ✅ Create user with default role "lecturer"
        User::create([
            'username' => $request->username,
            'email'    => $request->email,
            'nidn'     => $request->nidn,
            'password' => Hash::make($request->password),
            'role'     => 'lecturer',
        ]);

        // ✅ Redirect to login with success message
        return redirect('/login')->with('success', 'Registration successful! Please login.');
    }
}
