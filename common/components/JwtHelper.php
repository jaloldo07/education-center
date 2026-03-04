<?php
namespace common\components;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Yii;

class JwtHelper
{
    public static function generateToken($userId)
    {
        $secret = Yii::$app->params['jwtSecret'];
        $payload = [
            'user_id' => $userId,
            'iat' => time(),
            'exp' => time() + (7 * 24 * 60 * 60), // 7 kun
        ];
        
        return JWT::encode($payload, $secret, 'HS256');
    }

    public static function validateToken($token)
    {
        try {
            $secret = Yii::$app->params['jwtSecret'];
            $decoded = JWT::decode($token, new Key($secret, 'HS256'));
            return $decoded->user_id;
        } catch (\Exception $e) {
            return false;
        }
    }
}