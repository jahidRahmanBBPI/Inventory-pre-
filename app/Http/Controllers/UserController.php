<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\User;
use Exception;
use Hash;
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

    function UserLogin(Request $request)
    {
        // $request->validate([
        //     'email' => 'required|string|email|max:255',
        //     'password' => 'required|string|min:3',
        // ]);

        $count = User::where('email', $request->email)->count();
        if($count == 1){
            $user = User::where('email', $request->email)->first();
            if(Hash::check($request->password, $user->password)){
                // User Login -> jwt token issue
                $token = JWTToken::CreateToken($request->email);
                return response()->json([
                    'status' => 'success',
                    'message' => 'User Login Successfully',
                    'token' => $token,
                ], 200);
            } else {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Invalid email or password',
                ], 200);
            }
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid email or password',
            ], 200);
        }

        // $user = User::where('email', $request->email)->first();
        // if (!$user || !Hash::check($request->password, $user->password)) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Invalid email or password',
        //     ], 401);
        // }

        // Generate JWT token
        $jwtToken = new \App\Helper\JWTToken();
        
        // $token = $jwtToken->CreateToken($user->email);

    }
}
