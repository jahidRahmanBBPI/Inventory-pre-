<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Mail\OTPMail;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    function register(Request $request)
    {
    // dd($request->all());
    // $request->validate([
    //     'firstName' => 'required|string|max:255',
    //     'lastName' => 'required|string|max:255',
    //     'email' => 'required|string|email|max:255|unique:users',
    //     'password' => 'required|string|min:8',
    //     'mobile' => 'required|string|max:50|unique:users',
    // ]);    
    //  dd($request->all());

    
    try {
        
        User::create([
        'firstName' => $request->firstName,
        'lastName' => $request->lastName,
        'email' => $request->email,
        'password' => Hash::make($request->password),
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

    function UserLogin(Request $request) {
    $user = User::where('email', $request->email)->first();
    // dd([
    //     'input_email' => $request->email,
    //     'input_password' => $request->password,
    //     'user_found' => $user ? true : false,
    //     'database_password' => $user?->password,
    //     'password_match' => $user
    //         ? Hash::check($request->password, $user->password)
    //         : false,
    // ]);

    
    if (!$user) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Invalid email or password',
        ], 401);
    }

    // User input password vs database hashed password
    if (!Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => 'failed',
            'message' => 'Invalid email or password',
        ], 401);
    }

    // Password correct
    $token = JWTToken::CreateToken($user->email);

    return response()->json([
        'status' => 'success',
        'message' => 'User Login Successfully',
        'token' => $token,
    ], 200);
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

    function VerifyOTP(Request $request){
        $email = $request->input('email');
        $otp = $request->input('otp');
        $count = User::where('email', '=', $email)->where('otp', '=', $otp)->count();
        if($count == 1){
            // Database OTP Update
            User::where('email', '=', $email)->update(['otp' => '0']);

            // Pass Reset Token Issue
            $token = JWTToken::CreateTokenForSetPassword($request->input('email'));
            return response()->json([
                'status' => 'success',
                'message' => 'OTP verified successfully',
                'token' => $token,
            ], 200);
        }
        else {
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid OTP',
            ], 200);
        }
    }

    function ResetPass(Request $request){
        try{
            // $email = $request->header('email');
            $token = $request->header('token');
            $email = JWTToken::VerifyToken($token);
            $password = $request->input('password');
            // return ([$email, $password]);
            User::where('email', '=', $email)->update(['password' => Hash::make($password)]);
            return response()->json([
                'status'=>'success',
                'message'=>'Password Reset Successful'
            ]);
        }catch(Exception $e){
            return response()->json([
                'status'=>'fail',
                'message'=>'Something Went Wrong!',
            ]);
        }
        
    }
}
