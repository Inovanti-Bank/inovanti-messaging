<?php

namespace InovantiBank\Messaging\Contracts;

use InovantiBank\Messaging\DTOs\MessageData;

interface MessagingChannelInterface
{
    public function send(MessageData $messageData): array;
}
