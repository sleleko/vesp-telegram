<?php
declare(strict_types=1);

namespace App\Controllers\Web;

use Longman\TelegramBot\Exception\TelegramLogException;
use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;
use Vesp\Controllers\ModelGetController;
//use App\Services\Telegram;

class TelegramHook extends ModelGetController
{
    public function get()
    {
        try {
            // Initialize telegram API Object
            $telegram = new Telegram(getenv('TG_ACCESS_TOKEN'), getenv('TG_BOT_USERNAME'));

            // Set custom commands path
            $telegram->addCommandsPath('');

            // Set bot admins id
            $telegram->enableAdmins([getenv('TG_ADMIN_USERS_ID')]);

            // Handle telegram webhook request
            $telegram->handle();
        } catch (TelegramException $e) {
            return $this->failure($e->getMessage());
        }
    }
}