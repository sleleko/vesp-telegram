<?php

namespace App\Controllers\Web;

use Psr\Http\Message\ResponseInterface;
use Vesp\Controllers\ModelGetController;

class TelegramHook extends ModelGetController
{
    public function get(): ResponseInterface
    {
        return $this->success('telegram');
    }
}