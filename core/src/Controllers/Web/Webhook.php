<?php

namespace App\Controllers\Web;

use App\Services\Telegram;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Http\Message\ResponseInterface;
use Throwable;
use Vesp\Controllers\Controller;
use Vesp\Services\Eloquent;

class Webhook extends Controller
{
    protected Telegram $telegram;

    public function __construct(Eloquent $eloquent, Telegram $telegram)
    {
        parent::__construct($eloquent);
        $this->telegram = $telegram;
    }

    /**
     * @throws Throwable
     */
    public function post(): ResponseInterface
    {
        try {
            $this->telegram->handle();
        } catch (Throwable $e) {
//            TODO: Логирование ошибок
//            return $this->failure($e->getMessage());
            new Logger('telegram_bot', [
                (new StreamHandler(getenv('LOG_DIR'), Logger::DEBUG))->setFormatter(new LineFormatter(null, null, true)),
                (new StreamHandler(getenv('LOG_DIR'), Logger::ERROR))->setFormatter(new LineFormatter(null, null, true)),
            ]);
            // Updates logger for raw updates.
            new Logger('telegram_bot_updates', [
                (new StreamHandler(getenv('LOG_DIR'), Logger::INFO))->setFormatter(new LineFormatter('%message%' . PHP_EOL)),
            ]);
        }

        return $this->success(['Подключено' => $this->telegram->getCommandsList()]);
    }
}