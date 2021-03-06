<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use Longman\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;
use Vesp\Controllers\ModelGetController;

class TelegramUnSetHook extends ModelGetController
{
    protected $scope = 'users';
    public function get()
    {
        try {
            // Initialize telegram API Object
            $telegram = new Telegram(getenv('TG_ACCESS_TOKEN'), getenv('TG_BOT_USERNAME'));

            // Deleting up web hook
            $result = $telegram->deleteWebhook();

            // Checking result of setup and return success response if its OK
            if ($result->isOk()) {
                return $this->success($result->getDescription());
            }
            // or return error message if something was wrong
        } catch (TelegramException $e) {
            return $this->failure($e->getMessage());
        }
    }
}