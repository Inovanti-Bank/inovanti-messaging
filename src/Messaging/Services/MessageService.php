<?php

namespace InovantiBank\Messaging\Services;

use Exception;
use Illuminate\Events\Dispatcher;
use InovantiBank\Messaging\Contracts\MessagingChannelInterface;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Events\MessageFailed;
use InovantiBank\Messaging\Events\MessageSent;
use InovantiBank\Messaging\Exceptions\MessagingException;

class MessageService
{
    protected array $channels;

    protected Dispatcher $dispatcher;

    public function __construct(array $channels, Dispatcher $dispatcher)
    {
        $this->channels = $channels;
        $this->dispatcher = $dispatcher;
    }

    public function setMockProvider(string $type, MessagingChannelInterface $service)
    {
        $this->channels[$type] = $service;
    }

    public function send(MessageData $messageData): array
    {
        if (! isset($this->channels[$messageData->type])) {
            $this->dispatcher->dispatch(new MessageFailed($messageData, "Nenhum provedor configurado para o tipo: {$messageData->type}"));
            throw new MessagingException("Nenhum provedor configurado para o tipo: {$messageData->type}");
        }

        try {
            /** @var MessagingChannelInterface $channel */
            $channel = $this->channels[$messageData->type];

            $response = $channel->send($messageData);

            $this->dispatcher->dispatch(new MessageSent($messageData));

            return $response;
        } catch (Exception $e) {
            $this->dispatcher->dispatch(new MessageFailed($messageData, $e->getMessage()));
            throw new MessagingException('Erro ao enviar mensagem: '.$e->getMessage(), 0, $e);
        }
    }
}
