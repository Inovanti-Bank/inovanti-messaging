<?php

namespace InovantiBank\Messaging\Services;

use InovantiBank\Messaging\Contracts\MessagingChannelInterface;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\TwilioProvider;

class TwilioSmsService implements MessagingChannelInterface
{
    protected TwilioProvider $provider;

    public function __construct(TwilioProvider $provider)
    {
        $this->provider = $provider;
    }

    public function send(MessageData $messageData): array
    {
        return $this->provider->sendMessage($messageData);
    }
}
