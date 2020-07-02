<?php

declare(strict_types=1);

namespace App\Models;

/**
 * Class User
 * @package App\Models
 * @property string $fullname
 * @property string $phone
 * @property string $email
 * @property string $telegram
 */

class User extends \Vesp\Models\User
{
    protected $fillable = ['fullname','phone','email','telegram'];
}