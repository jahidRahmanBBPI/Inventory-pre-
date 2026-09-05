<?php 
namespace App\Helper;

use App\Models\User;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;

class JWTToken{

    public static function CreateToken($userEmail, $id):string{
        $key = env('JWT_KEY');
        $payload = [
            'iss' => 'laravel-token', // Issuer
            'iat' => time(), // Issued at
            'exp' => time() + 3600*24*2, // Expiration time (2 day)
            'userEmail' => $userEmail, // User email
            'userID' => $id
        ];

        return JWT::encode($payload, $key, 'HS256');
    }

    public static function VerifyToken($token):string|object{
        try {
            if($token == null){
                return 'Unauthorized';
            }else{
                $key = env('JWT_KEY');
            $decode = JWT::decode($token, new Key($key, 'HS256'));
            // return $decode->userEmail;
            return $decode;
            }
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
            'userID' => '0'
        ];
        return JWT::encode($payload, $key, 'HS256');
    }
    

}