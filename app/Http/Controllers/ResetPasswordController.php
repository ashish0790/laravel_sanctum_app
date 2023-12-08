<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;

class ResetPasswordController extends Controller
{
    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));
        return $status === Password::RESET_LINK_SENT ? response()->json(['status' => true, 'message' => 'Reset link sent to your email'], 200) : response()->json(['status' => false, 'message' => 'Unable to send reset link'], 400);
    }

    public function resetPassword(Request $request, $token)
    {
        // $token = $request->query('token');
        // return dd($token);
    }

    public function resetPost(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
        ]);

        $status = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function (User $user, string $password) {
            $user
                ->forceFill([
                    'password' => Hash::make($password),
                ])
                ->setRememberToken(Str::random(30));

            $user->save();

            event(new PasswordReset($user));
        });
        return $status === Password::PASSWORD_RESET 
        ? response()->json(['status' => true, 'message' => 'password change successfully'], 200)
        : response()->json(['status' => false, 'message' => 'password does not change'], 400);
    }

    public function loginpasswordChange(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|different:current_password',
            'new_password_confirmation' => 'required|same:new_password',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(['status' => false, 'error' => 'Current password is incorrect'], 401);
        }

        $user->update(['password' => bcrypt($request->input('new_password'))]);

        return response()->json(['status' => true, 'message' => 'Password changed successfully'], 200);
    }
}
