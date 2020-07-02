<?php

declare(strict_types=1);

namespace App\Controllers\User;

use Psr\Http\Message\ResponseInterface;

class Profile extends \Vesp\Controllers\User\Profile
{
    /**
     * @return ResponseInterface
     */
    public function get()
    {
        if ($this->user) {
            $data = $this->user->toArray();
            $data += ['scope' => $this->user->role->scope];

            return $this->success(['user' => $data]);
        }

        return $this->failure('Authentication required', 401);
    }

    /**
     * @return ResponseInterface
     */
    public function patch()
    {
        $this->user->fill(
            [
                'email' => trim($this->getProperty('email')),
                'fullname' => trim($this->getProperty('fullname')),
                'phone' => preg_replace('#[^0-9]#', '', $this->getProperty('phone')),
                'telegram' => trim($this->getProperty('telegram')),
            ]
        );
        if ($password = trim($this->getProperty('password'))) {
            $this->user->password = $password;
        }
        $this->user->save();

        return $this->get();
    }
}