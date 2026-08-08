<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;

class UserController extends Controller
{
    function register(Request $request)
    {

    // $request->validate([
    //     'firstName' => 'required|string|max:255',
    //     'lastName' => 'required|string|max:255',
    //     'email' => 'required|string|email|max:255|unique:users',
    //     'password' => 'required|string|min:8',
    //     'mobile' => 'required|string|max:50|unique:users',
    // ]);    

    try {
        User::create([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'mobile' => $request->mobile,
        
    ]);
        return response()->json([
            'status' => 'success',
            'message' => 'User Registered Successfully',
            ]);
    
    } catch (Exception $e) {
        return response()->json([

            'status' => 'Failed to create user', 
            // 'message' => $e->getMessage()
            'message' => 'User Registration Failed. Please try again later.'

            ]);
    }
           
    }
}
