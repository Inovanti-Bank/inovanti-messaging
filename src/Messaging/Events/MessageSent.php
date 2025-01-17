<?php

namespace InovantiBank\Messaging\Events;

use Illuminate\Foundation\Events\Dispatchable;
use InovantiBank\Messaging\DTOs\MessageData;

class MessageSent
{
    use Dispatchable;

    public MessageData $messageData;

    public function __construct(MessageData $messageData)
    {
        $this->messageData = $messageData;
    }
}
