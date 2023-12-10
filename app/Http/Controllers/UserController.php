<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
  function register(Request $request)
  {
    $validate = $request->validate([
      'name' => 'required',
      'email' => 'required',
      'password' => 'required',
      'password_confirmation' => 'required|same:password',
    ]);
    $user = User::create($validate);
    return response()->json([
      'created' => true,
      'user' => $user
    ],201);
  }

  function login(Request $request)
  {
    $validate = $request->validate([
      'email' => 'required',
      'password' => 'required'
    ]);

    if(!auth()->attempt($validate)) {
      return response()->json([
        'status' => false,
        'message' => 'Invalid credentials.'
      ], 400);
    }

    $token = auth()->user()->createToken('auth_token')->accessToken;
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
    $request->user()->token()->revoke();
    return response()->json([
      'revoke' => true,
      'message' => 'User logged out successfully.'
    ]);
  }
}
