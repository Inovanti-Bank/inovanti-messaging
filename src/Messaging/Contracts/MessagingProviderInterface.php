<?php

namespace InovantiBank\Messaging\Contracts;

use InovantiBank\Messaging\DTOs\MessageData;

interface MessagingProviderInterface
{
    /**
     * Envia uma mensagem utilizando o provedor de mensageria.
     *
     * @param  MessageData  $messageData  Objeto contendo os dados da mensagem.
     * @return array Resposta padronizada do provedor.
     */
    public function sendMessage(MessageData $messageData): array;
}
