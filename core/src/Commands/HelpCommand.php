<?php

namespace App\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;
use Longman\TelegramBot\Telegram;

class HelpCommand extends UserCommand
{
    protected $name = 'help';
    protected $description = 'Вывод сообщения со списком команд';
    protected $usage = '/help';

    public function execute(): ServerResponse
    {
        if ($callback = $this->getCallbackQuery()) {
            $message = $callback->getMessage();

            $data = array_slice(explode('::', $callback->getData()), 1);
            $method = array_shift($data);
            $response = $method && method_exists($this, $method)
                ? $this->$method(...$data)
                : $this->getBrands();

            if (is_array($response)) {
                $response['chat_id'] = $message->getChat()->getId();
                $response['message_id'] = $message->getMessageId();

                return Request::editMessageText($response);
            }

            return Request::sendMessage([
                'chat_id' => $message->getChat()->getId(),
                'message_id' => $message->getMessageId(),
                'text' => 'Какая-то ошибка, увы...',
            ]);
        }
        return $this->replyToChat('',$this->getHelpCommands());
    }

    /**
     * @throws TelegramException
     */
    protected function getHelpCommands(): array
    {
        /** @var UserCommand[] $commands */
        $commands = $this->telegram->getCommandsList();
        $data = [];
        foreach ($commands as $command) {
            if ($command->showInHelp() && $command->getUsage()) {
                $data[] = [
                    'text' => $command->getDescription(),
                    'callback_data' => [$command->getUsage() => 'getHelpCommand'],
                ];
            }
        }
        return [
            'text' => 'Все доступные команды',
            'reply_markup' => new InlineKeyboard(...$this->prepareKeyboard($data)),
        ];
    }

    protected function getHelpCommand($command): array
    {
        $rows = [
            ['text' => 'Вернуться назад', 'callback_data' => $this->usage],
        ];

        return [
            'text' => 'Выберите',
            'reply_markup' => new InlineKeyboard(...$this->prepareKeyboard($rows)),
        ];
    }

    protected function prepareKeyboard($rows): array
    {
        $array = [];

        $i = 0;
        $odd = true;
        $count = count($rows);
        while ($i <= $count) {
            $hasOne = !empty($rows[$i]);
            $hasTwo = $hasOne && !empty($rows[$i + 1]);

            if ($count >= 10) {
                if ($odd && $hasTwo && !empty($rows[$i + 2])) {
                    $array[] = [$rows[$i], $rows[$i + 1], $rows[$i + 2]];
                    $odd = false;
                    $i += 3;
                } elseif ($hasTwo) {
                    $array[] = [$rows[$i], $rows[$i + 1]];
                    $odd = true;
                    $i += 2;
                } elseif ($hasOne) {
                    $array[] = [$rows[$i]];
                    ++$i;
                } else {
                    break;
                }
            } elseif ($hasTwo) {
                $array[] = [$rows[$i], $rows[$i + 1]];
                $i += 2;
            } elseif ($hasOne) {
                $array[] = [$rows[$i]];
                ++$i;
            } else {
                break;
            }
        }

        return $array;
    }
}