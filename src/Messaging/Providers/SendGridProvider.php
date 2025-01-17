<?php

namespace InovantiBank\Messaging\Providers;

use Exception;
use InovantiBank\Messaging\Contracts\MessagingProviderInterface;
use InovantiBank\Messaging\DTOs\MessageData;
use SendGrid;
use SendGrid\Mail\Mail;

class SendGridProvider implements MessagingProviderInterface
{
    protected SendGrid $sendGrid;

    public function __construct(string $apiKey)
    {
        $this->sendGrid = new SendGrid($apiKey);
    }

    /**
     * Método para definir um mock do SendGrid (para testes)
     */
    public function setMockInstance(SendGrid $sendGrid)
    {
        $this->sendGrid = $sendGrid;
    }

    /**
     * Envia um e-mail via SendGrid.
     *
     * @throws Exception
     */
    public function sendMessage(MessageData $messageData): array
    {
        try {
            $email = new Mail;
            $email->setFrom($messageData->from);
            $email->setSubject($messageData->metadata['subject'] ?? 'No Subject');
            $email->addTo($messageData->to);
            $email->addContent('text/plain', $messageData->content);
            $email->addContent('text/html', "<p>{$messageData->content}</p>");

            $response = $this->sendGrid->send($email);

            return [
                'status' => $response->statusCode() === 202 ? 'success' : 'error',
                'message_id' => $response->headers()['X-Message-Id'][0] ?? null,
                'to' => $messageData->to,
                'from' => $messageData->from,
                'type' => 'email',
                'http_status' => $response->statusCode(),
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'response' => method_exists($e, 'getResponse') ? $e->getResponse() : null,
            ];
        }
    }
}
