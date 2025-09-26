<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    /**
     * Show forgot password form
     */
    public function showForgotForm()
    {
        return view('auth.forgot');
    }

    /**
     * Handle sending of reset code
     */
    public function sendCode(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return back()->with('error', 'Email not found.');
        }

        $code = rand(100000, 999999);

        DB::table('reset')->updateOrInsert(
            ['email' => $user->email],
            ['token' => $code, 'created_at' => now()]
        );

        session(['password_reset_email' => $user->email]);
        session()->forget('password_reset_verified'); // clear old verification

        // Send email
        Mail::raw("Your password reset code is: $code", function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Password Reset Code');
        });

        return redirect()->route('verify.form');
    }

    /**
     * Show verify code form
     */
    public function showVerifyForm()
    {
        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('forgot.form')->with('error', 'Please enter your email first.');
        }
        return view('auth.verify', compact('email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate(['code' => 'required']);

        $email = session('password_reset_email');
        if (!$email) {
            return redirect()->route('forgot.form')->with('error', 'Session expired. Please request a new code.');
        }

        $record = DB::table('reset')->where('email', $email)->first();

        if (!$record) {
            return back()->with('error', 'No code found for this email. Please request a new code.');
        }

        if (Carbon::now()->gt(Carbon::parse($record->created_at)->addSeconds(60))) {
            return back()->with('error', 'Code has expired. Please request a new code.');
        }

        if ($request->code != $record->token) {
            return back()->with('error', 'Invalid code.');
        }

        DB::table('reset')->where('email', $email)->delete();

        session(['password_reset_verified' => true]);

        return redirect()->route('reset.form');
    }

    public function showResetForm()
    {
        $email = session('password_reset_email');
        $verified = session('password_reset_verified');

        if (!$email || !$verified) {
            return redirect()->route('forgot.form')->with('error', 'Unauthorized access. Please restart the reset process.');
        }

        return view('auth.reset', compact('email'));
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $email = session('password_reset_email');
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->with('error', 'Something went wrong. Try again.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        session()->forget(['password_reset_email', 'password_reset_verified']);

        return redirect('/login')->with('success', 'Password has been reset successfully!');
    }
}