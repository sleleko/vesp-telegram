<?php

declare(strict_types=1);

namespace App\Controllers\Admin;
use Telegram\Bot\Api;
use Vesp\Helpers\Env;

class BotSettings extends Api
{
    function setWebHookAddress()
    {
        $telegram = new Api(get.Env::loadFile('../.env'));
    }
}