<?php 
namespace App\Helper;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JWTToken{

    public static function CreateToken($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', // Issuer
            'iat' => time(), // Issued at
            'exp' => time() + 3600, // Expiration time (1 hour)
            'userEmail' => $userEmail, // User email
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function VerifyToken($token){
        try {

            $key = env('JWT_KEY');
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            return $decode->userEmail;
            } 

        catch (Exception $e) {
            return 'Unauthorized';
        }
    }

    public static function CreateTokenForSetPassword($userEmail):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', // Issuer
            'iat' => time(), // Issued at
            'exp' => time() + 60*20, // Expiration time (20 minutes)
            'userEmail' => $userEmail, // User email
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    

}