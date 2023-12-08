<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['status' => true, 'message' => 'you are register successfully', 'data' => $user], 200);
    }
    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
        $token = $user->createToken('API Token OF ' . $user->name)->plainTextToken;
        return response()->json(['status' => true, 'message' => 'you are login successfully', 'data' => $user, 'token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();

        if ($user) {
            $user->tokens()->delete();

            return response()->json(['status' => true, 'message' => 'Logout successful'], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'User not found'], 404);
        }
    }

    public function curUserDetail()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json(['status' => true, 'message' => 'user get successfully', 'user' => $user]);
        } else {
            return response()->json(['status' => false, 'message' => 'User not authenticated'], 401);
        }
    }
}
