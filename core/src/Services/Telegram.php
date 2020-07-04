<?php
declare(strict_types=1);

namespace App\Services;

class Telegram extends \Longman\TelegramBot\Telegram
{
    public function __construct()
    {
        parent::__construct(getenv('TG_ACCESS_TOKEN'),getenv('TG_BOT_USERNAME'));
    }
}