<?php

namespace App\Commands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class CallbackqueryCommand extends \Longman\TelegramBot\Commands\SystemCommands\CallbackqueryCommand
{
    /**
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        // Получаем объект callback query из API Telegram
        $callback = $this->getCallbackQuery();
        // И сообщение, в котором нажали на кнопку
        $message = $callback->getMessage();

        // Здесь мы получаем строку с командой и разбиваем её на состовляющие через разделитель '::'
        if ($data = explode('::', $callback->getData())) {
            // Первым должно идти имя команды
            $command = array_shift($data);
            // Оно начинается со слэша
            if (str_starts_with($command, '/')) {
                // И дальше пусть уже сама команда разбирается с запросом
                return $this->getTelegram()->executeCommand(substr($command, 1));
            }
        }

        // Если указаной команды нет, или действие неправильно указано
        // просто возвращаем в чат что нам пришло, для отладки
        return Request::sendMessage([
            'chat_id' => $message->getChat()->getId(),
            'text' => $data,
        ]);
    }
}