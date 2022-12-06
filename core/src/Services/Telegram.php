<?php

namespace App\Services;

class Telegram extends \Longman\TelegramBot\Telegram
{
    public function __construct()
    {
        parent::__construct(getenv('TG_ACCESS_TOKEN'), getenv('TG_BOT_USERNAME'));

        // Добавление команд
        $this->addCommandsPath(BASE_DIR . 'core/src/Commands');
    }
}