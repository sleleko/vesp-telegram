<?php

declare(strict_types=1);

namespace App\Controllers\Security;

use App\Models\UserToken;
use Vesp\Helpers\Jwt;

class Login extends \Vesp\Controllers\Security\Login
{
    public function post()
    {
        // Invalidate old tokens
        UserToken::query()
            ->where('active', true)
            ->where('valid_till', '<', date('Y-m-d H:i:s', time()))
            ->update(['active' => false]);

        $response = parent::post();
        if ($response->getStatusCode() === 200) {
            $token = json_decode($response->getBody()->__toString())->token;
            if ($decoded = JWT::decodeToken($token)) {
                $user_token = new UserToken(
                    [
                        'user_id' => $decoded->id,
                        'token' => $token,
                        'valid_till' => date('Y-m-d H:i:s', $decoded->exp),
                        'ip' => $this->request->getAttribute('ip_address'),
                    ]
                );
                $user_token->save();

                // Limit active tokens
                $max = getenv('JWT_MAX');
                if ($max && UserToken::query()->where(['user_id' => $decoded->id, 'active' => true])->count() > $max) {
                    UserToken::query()
                        ->where(['user_id' => $decoded->id, 'active' => true])
                        ->orderBy('updated_at', 'asc')
                        ->orderBy('created_at', 'asc')
                        ->first()
                        ->update(['active' => false]);
                }
            }
        }

        return $response;
    }
}