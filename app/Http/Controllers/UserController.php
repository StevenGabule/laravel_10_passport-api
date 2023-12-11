<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
  function register(Request $request)
  {
    $validate = $request->validate([
      'name' => 'required',
      'email' => 'required',
      'password' => ['required', 'confirmed'],
    ]);

    $validate['password'] = Hash::make($request->password);
    $user = User::create($validate);

    return response()->json([
      'created' => true,
      'user' => $user
    ],201);
  }

  function login(Request $request)
  {
    $request->validate([
      'email' => ['required', 'email'],
      'password' => ['required']
    ]);

    $user = User::where('email', $request->email)->first();

    if(!$user || !Hash::check($request->password, $user->password)) {
      throw ValidationException::withMessages([
        'email' => ['The provided credentials are incorrect.']
      ]);
    }

    $token = $user->createToken('auth_token')->accessToken;

    return response()->json([
      'status' => true,
      'message' => 'User logged in successfully.',
      'access_token' => $token
    ]);
  }

  function profile()
  {
    return response()->json(['user' => auth()->user()]);
  }

  function logout(Request $request)
  {
    $request->user()->tokens()->delete();
    return response()->json([
      'revoke' => true,
      'message' => 'User logged out successfully.'
    ]);
  }
}
