<?php

namespace App\Services;

use App\Services\Interfaces\NotificationServiceInterface;
use App\Enums\Messages;
use Monolog\Logger;
use Monolog\Handler\LogglyHandler;

class NotificationService implements NotificationServiceInterface
{
    private $log;

    public function __construct()
    {
        $this->log = new Logger('Notification');
        $this->log->pushHandler(new LogglyHandler(env('LOGGLY_TOKEN'), Logger::INFO));
    }

    public function processMessage(array $data): string
    {
        $message = sprintf(
            '%s, %s %s',
            $data['name'],
            Messages::NOTIFICATION_TO_SEND,
            $data['value']
        );

        $payload = [
            'send_to' => $data['email'],
            'message' => $message
        ];

        $this->send($payload);

        return Messages::NOTIFICATION_SUCCESSFULLY;
    }

    public function send(array $payload): void
    {
        $this->log->info(json_encode($payload));
    }
}