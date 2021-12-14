<?php

namespace App\Http\Controllers;

//panggil model User
use App\Models\User;
//untuk proses login
use Illuminate\Support\Facades\Auth;
//untuk validasi
use Illuminate\Support\Facades\Validator;
//untuk hash password 
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function register(Request $request)
    {
      //validasi
      $data = Validator::make($request->all(), [
        'email' => 'required|email|unique:users',
        'name' => 'required',
        'password' => 'required|min:8',
        'confirmPassword' => 'required|same:password'
      ]);
      
      //jika validasi gagal
      if($data->fails()) {
        return response()->json($data->errors(), 422);
      }
      
      //save
      $user = User::create([
        'email' => $request->email,
        'name' => $request->name,
        'password' => hash::make($request->password)
      ]);
      
      return response()->json([
        'message' => 'user created successfuly',
        'user' => $user
      ]);
    }
    
    public function login(Request $request) 
    {
      $data = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|min:8'
      ]);
      
      //jika validasi gagal
      if($data->fails()) {
        return response()->json($data->errors(), 422);
      }
      
      //jika login gagal
      if(!Auth::attempt($request->only(['email', 'password']) )) {
        return response()->json(['userNotFound' => 'Wrong username or password'], 410);
      }
      
      //jika login berhasil
      return response()->json([
        'message' => 'login successfuly',
        'user' => User::where('email', $request->email)->first()
      ], 200);
    }
    
    public function logout(Request $request)
    {
      $request->session()->invalidate();
      return response()->json('logout successfuly');
    }
}
