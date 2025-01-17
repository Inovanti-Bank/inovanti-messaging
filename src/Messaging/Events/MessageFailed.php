<?php

namespace InovantiBank\Messaging\Events;

use Illuminate\Foundation\Events\Dispatchable;
use InovantiBank\Messaging\DTOs\MessageData;

class MessageFailed
{
    use Dispatchable;

    public MessageData $messageData;

    public string $errorMessage;

    public function __construct(MessageData $messageData, string $errorMessage)
    {
        $this->messageData = $messageData;
        $this->errorMessage = $errorMessage;
    }
}
