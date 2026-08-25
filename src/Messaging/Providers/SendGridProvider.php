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
        $subject = $messageData->subject ?? $messageData->metadata['subject'] ?? 'No Subject';
        $customArgs = $messageData->customArgs ?? [];

        if ($messageData->correlationId !== null) {
            $customArgs['correlation_id'] = $messageData->correlationId;
        }

        try {
            $email = new Mail;
            $email->setFrom($messageData->from, $messageData->fromName);
            $email->setSubject($subject);
            $email->addTo($messageData->to);
            $email->addContent('text/plain', $messageData->content);
            $email->addContent('text/html', "<p>{$messageData->content}</p>");

            if ($messageData->replyTo !== null) {
                $email->setReplyTo($messageData->replyTo, $messageData->replyToName);
            }

            foreach ($messageData->categories ?? [] as $category) {
                $email->addCategory($category);
            }

            foreach ($customArgs as $key => $value) {
                $email->addCustomArg($key, $value);
            }

            foreach ($messageData->headers ?? [] as $key => $value) {
                $email->addHeader($key, $value);
            }

            if (isset($messageData->addCC)) {
                foreach ($messageData->addCC as $cc) {
                    $email->addCc($cc);
                }
            }

            if (isset($messageData->addBCC)) {
                foreach ($messageData->addBCC as $bcc) {
                    $email->addBcc($bcc);
                }
            }

            if (isset($messageData->addAttachments) && ! empty($messageData->addAttachments)) {
                foreach ($messageData->addAttachments as $attachment) {
                    $email->addAttachment(
                        attachment: $attachment->base64_file,
                        filename: $this->sanitizeFileName($attachment->file_name),
                        type: $attachment->file_type->value
                    );
                }
            }

            $response = $this->sendGrid->send($email);
            $responseHeaders = $this->headersToArrayAssoc($response->headers());
            $messageId = $this->findHeader($responseHeaders, 'X-Message-Id');

            return [
                'status' => $response->statusCode() === 202 ? 'success' : 'error',
                'message_id' => $messageId,
                'x_message_id' => $messageId,
                'to' => $messageData->to,
                'from' => $messageData->from,
                'type' => 'email',
                'http_status' => $response->statusCode(),
                'cc' => $messageData->addCC ?? null,
                'bcc' => $messageData->addBCC ?? null,
                'subject' => $subject,
                'from_name' => $messageData->fromName,
                'reply_to' => $messageData->replyTo,
                'reply_to_name' => $messageData->replyToName,
                'categories' => $messageData->categories ?? [],
                'custom_args' => $customArgs,
                'headers' => $messageData->headers ?? [],
                'correlation_id' => $messageData->correlationId,
                'metadata' => $messageData->metadata ?? [],
                'response_headers' => $responseHeaders,
                'response_body' => $this->decodeResponseBody($response->body()),
            ];
        } catch (Exception $e) {
            $errorResponse = method_exists($e, 'getResponse') ? $e->getResponse() : null;

            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'response' => $errorResponse ? $errorResponse->getBody()->getContents() : null,
                'to' => $messageData->to,
                'from' => $messageData->from,
                'type' => 'email',
                'subject' => $subject,
                'correlation_id' => $messageData->correlationId,
                'custom_args' => $customArgs,
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

            $message = json_decode($response->body(), true);

            if (! is_array($message)) {
                throw new Exception('Resposta inválida ao buscar detalhes da mensagem.');
            }

            $message['message_id'] ??= $message['msg_id'] ?? $message['sg_message_id'] ?? $message['id'] ?? $msgId;
            $message['custom_args'] ??= $this->normalizeCustomArgs($message['unique_args'] ?? []);
            $message['http_status'] = $response->statusCode();

            return $message;
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'error' => $e->getMessage(),
                'http_status' => isset($response) ? $response->statusCode() : null,
                'message_id' => $msgId,
                'response_body' => isset($response) ? $this->decodeResponseBody($response->body()) : null,
            ];
        }
    }

    private function headersToArrayAssoc(array $headers): array
    {
        $headersAssoc = [];

        foreach ($headers as $key => $value) {
            if (is_string($key)) {
                $headersAssoc[$key] = is_array($value)
                    ? implode(', ', $value)
                    : (string) $value;

                continue;
            }

            if (is_string($value) && str_contains($value, ':')) {
                [$headerKey, $headerValue] = explode(':', $value, 2);
                $headersAssoc[trim($headerKey)] = trim($headerValue);
            }
        }

        return $headersAssoc;
    }

    private function findHeader(array $headers, string $name): ?string
    {
        foreach ($headers as $key => $value) {
            if (strcasecmp($key, $name) === 0) {
                return trim($value);
            }
        }

        return null;
    }

    private function decodeResponseBody(string $body): array|string|null
    {
        if ($body === '') {
            return null;
        }

        $decoded = json_decode($body, true);

        return json_last_error() === JSON_ERROR_NONE && is_array($decoded) ? $decoded : $body;
    }

    private function normalizeCustomArgs(array|string $customArgs): array
    {
        if (is_array($customArgs)) {
            return $customArgs;
        }

        $decoded = json_decode($customArgs, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function sanitizeFileName(string $fileName)
    {
        $sanitizedFileName = $fileName;

        $sanitizedFileName = preg_replace('/[^A-Za-z0-9_.]/', '_', $sanitizedFileName);
        $sanitizedFileName = preg_replace('/_+/', '_', $sanitizedFileName);
        $sanitizedFileName = trim($sanitizedFileName, '_');

        return $sanitizedFileName;
    }
}
