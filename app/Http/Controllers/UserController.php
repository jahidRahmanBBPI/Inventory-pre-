<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

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
    }

    function SendOTPCode_chat (Request $request)
    {
        $request->validate([
            'email' => 'required|string|email|max:255',
        ]);

        $count = User::where('email', $request->email)->count();
        if($count == 1){
            $user = User::where('email', $request->email)->first();
            // Generate OTP
            $otp = rand(100000, 999999);
            // Send OTP to user email
            Mail::to($user->email)->send(new \App\Mail\OTPMail($otp));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent to your email',
                'otp' => $otp,
            ], 200);
        } else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid email',
            ], 200);
        }
    }

    function SendOTPCode(Request $request){
        $email = $request->email;
        $otp = rand(100000, 999999);
        $count = User::where('email', '=', $email)->count();
        if($count == 1){
            //OTP send to email
            Mail::to($email)->send(new OTPMail($otp));
            // OTP code Table update
            User::where('email', '=', $email)->update(['otp' => $otp]);
            return response()->json([
                'status' => 'success',
                'message' => 'OTP sent to your email',
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid email',
            ], 200);
        }
    }

}
