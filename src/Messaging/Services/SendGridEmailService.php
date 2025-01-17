<?php

namespace InovantiBank\Messaging\Services;

use InovantiBank\Messaging\Contracts\MessagingChannelInterface;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\SendGridProvider;

class SendGridEmailService implements MessagingChannelInterface
{
    protected SendGridProvider $provider;

    public function __construct(SendGridProvider $provider)
    {
        $this->provider = $provider;
    }

    public function send(MessageData $messageData): array
    {
        return $this->provider->sendMessage($messageData);
    }
}
