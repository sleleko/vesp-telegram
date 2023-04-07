<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;

class StartCommand extends UserCommand
{
    protected $name = 'start';
    protected $description = 'Запуск бота';
    protected $usage = '/start';

    public function execute(): ServerResponse
    {
        $user = $this->getMessage()->getFrom();
        $data = [
            'Привет, ' . ($user->getFirstName()) . '!',
            'Это тестовый бот, написанный для тестирования возможностей нового АПИ Телеграмма.',
            'На данный момент бот отвечает раз в минуту. Используй /help, чтобы увидеть все доступные команды.',
        ];

//        return $this->replyToChat(implode(PHP_EOL . PHP_EOL, $data));
        return [
            'text' => 'Выберите бренд',
            'reply_markup' => new InlineKeyboard(...$this->prepareKeyboard($rows)),
        ];
    }
}