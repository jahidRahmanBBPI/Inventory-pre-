<?php

namespace App\Http\Middleware;

use App\Helper\JWTToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // $token = $request->header('token');
        $token = $request->cookie('token');
        $result = JWTToken::VerifyToken($token);
        if($token == null){
                return redirect('/UserLogin');
            }
        // if($result == "Unauthorized"){
            // return response()->json([
            //     'status'=>'failed',
            //     'message'=>'unauthorized'
            // ], 200);
            // return redirect('/UserLogin');
        // }
        else{
            $request->headers->set('id', $result->userID);
            $request->headers->set('email', $result->userEmail);
            return $next($request);
        }
        
    }
}
