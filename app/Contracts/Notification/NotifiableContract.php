<?php

namespace App\Contracts\Notification;

interface NotifiableContract
{
    public function sendNotification(string $channel, array $payload): void;
}
