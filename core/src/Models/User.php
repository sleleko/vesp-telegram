<?php

declare(strict_types=1);

namespace App\Models;

class User extends \Vesp\Models\User
{
    protected $fillable = ['fullname','telegram'];
}