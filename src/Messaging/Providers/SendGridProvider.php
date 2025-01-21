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
                'message_id' => $this->headersToArrayAssoc($response->headers())['X-Message-Id'] ?? null,
                'to' => $messageData->to,
                'from' => $messageData->from,
                'type' => 'email',
                'http_status' => $response->statusCode(),
            ];
        } catch (Exception $e) {
            $errorResponse = method_exists($e, 'getResponse') ? $e->getResponse() : null;

            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'response' => $errorResponse ? $errorResponse->getBody()->getContents() : null,
            ];
        }
    }

    /**
     * Obtém os detalhes de uma mensagem a partir do seu ID no SendGrid.
     */
    public function getMessageById(string $msgId): ?array
    {
        try {
            $response = $this->sendGrid->client
                ->messages()
                ->_($msgId)
                ->get();

            if ($response->statusCode() !== 200) {
                throw new Exception("Erro ao buscar detalhes da mensagem. Status HTTP: {$response->statusCode()}");
            }

            return json_decode($response->body(), true);
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'http_status' => isset($response) ? $response->statusCode() : null,
            ];
        }
    }

    private function headersToArrayAssoc(array $headers): array
    {
        $headersAssoc = [];

        foreach ($headers as $header) {
            if (strpos($header, ': ') !== false) {
                [$key, $value] = explode(': ', $header, 2);
                $headersAssoc[$key] = $value;
            }
        }

        return $headersAssoc;
    }
}
