<?php

/**
 * User "/image" command
 *
 * Randomly fetch any uploaded image from the Uploads path and send it to the user.
 */

namespace App\Commands;

use Longman\TelegramBot\Commands\UserCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;
use Longman\TelegramBot\Request;

class ImageCommand extends UserCommand
{
    /**
     * @var string
     */
    protected $name = 'image';

    /**
     * @var string
     */
    protected $description = 'Рандомно выводит любую загруженную картинку';

    /**
     * @var string
     */
    protected $usage = '/image';

    /**
     * @var string
     */
    protected $version = '1.2.0';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $message = $this->getMessage();

        // Use any extra parameters as the caption text.
        $caption = trim($message->getText(true));

        // Make sure the Upload path has been defined and exists.
        $upload_path = $this->telegram->getUploadPath();
        if (!is_dir($upload_path)) {
            return $this->replyToChat('Путь для загрузки не задан или не существует.');
        }

        // Get a random picture from the Upload path.
        $random_image = $this->getRandomImagePath($upload_path);
        if ('' === $random_image) {
            return $this->replyToChat('Не найдено каких либо изображений!');
        }

        // If no caption is set, use the filename.
        if ('' === $caption) {
            $caption = basename($random_image);
        }

        return Request::sendPhoto([
            'chat_id' => $message->getFrom()->getId(),
            'caption' => $caption,
            'photo'   => $random_image,
        ]);
    }

    /**
     * Return the path to a random image in the passed directory.
     *
     * @param string $dir
     *
     * @return string
     */
    private function getRandomImagePath(string $dir): string
    {
        if (!is_dir($dir)) {
            return '';
        }

        // Filter the file list to only return images.
        $image_list = array_filter(scandir($dir), static function ($file) {
            $extension = pathinfo($file, PATHINFO_EXTENSION);
            return in_array($extension, ['png', 'jpg', 'jpeg', 'gif']);
        });
        if (!empty($image_list)) {
            shuffle($image_list);
            return $dir . '/' . $image_list[0];
        }

        return '';
    }
}