<?php

namespace InovantiBank\Messaging\Providers;

use Exception;
use InovantiBank\Messaging\Contracts\MessagingProviderInterface;
use InovantiBank\Messaging\DTOs\MessageData;
use Twilio\Rest\Client;

class TwilioProvider implements MessagingProviderInterface
{
    protected Client $twilio;

    public function __construct(string $accountSid, string $authToken)
    {
        $this->twilio = new Client($accountSid, $authToken);
    }

    /**
     * Método para definir um mock do Twilio (para testes)
     */
    public function setMockInstance(Client $twilio)
    {
        $this->twilio = $twilio;
    }

    /**
     * Envia uma mensagem via Twilio (SMS ou WhatsApp).
     *
     * @throws Exception
     */
    public function sendMessage(MessageData $messageData): array
    {
        try {
            $from = $messageData->type === 'whatsapp'
                ? 'whatsapp:'.env('TWILIO_WHATSAPP_FROM')
                : env('TWILIO_SMS_FROM');

            $message = $this->twilio->messages->create(
                $messageData->type === 'whatsapp' ? 'whatsapp:'.$messageData->to : $messageData->to,
                [
                    'from' => $from,
                    'body' => $messageData->content,
                ]
            );

            return [
                'status' => 'success',
                'message_id' => $message->sid,
                'to' => $messageData->to,
                'from' => $from,
                'type' => $messageData->type,
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'twilio_response' => method_exists($e, 'getResponse') ? $e->getResponse() : null,
            ];
        }
    }
}
