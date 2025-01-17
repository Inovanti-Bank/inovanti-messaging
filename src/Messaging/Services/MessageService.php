<?php

namespace InovantiBank\Messaging\Services;

use Exception;
use InovantiBank\Messaging\Contracts\MessagingProviderInterface;
use InovantiBank\Messaging\DTOs\MessageData;
use InovantiBank\Messaging\Providers\SendGridProvider;
use InovantiBank\Messaging\Providers\TwilioProvider;

class MessageService
{
    protected array $providers = [];

    public function __construct(array $config)
    {
        $this->registerProviders($config);
    }

    /**
     * Registra os provedores disponíveis com base na configuração
     */
    protected function registerProviders(array $config): void
    {
        if (! empty($config['twilio']['account_sid']) && ! empty($config['twilio']['auth_token'])) {
            $this->providers['sms'] = new TwilioProvider(
                $config['twilio']['account_sid'],
                $config['twilio']['auth_token']
            );
            $this->providers['whatsapp'] = $this->providers['sms']; // Twilio pode enviar ambos
        }

        if (! empty($config['sendgrid']['api_key'])) {
            $this->providers['email'] = new SendGridProvider(
                $config['sendgrid']['api_key']
            );
        }
    }

    /**
     * Método para definir um mock dos provedores (para testes)
     */
    public function setMockProvider(string $type, MessagingProviderInterface $provider)
    {
        $this->providers[$type] = $provider;
    }

    /**
     * Envia uma mensagem utilizando o provedor correto
     *
     * @throws Exception
     */
    public function send(MessageData $messageData): array
    {
        if (! isset($this->providers[$messageData->type])) {
            throw new Exception("Nenhum provedor configurado para o tipo: {$messageData->type}");
        }

        /** @var MessagingProviderInterface $provider */
        $provider = $this->providers[$messageData->type];

        return $provider->sendMessage($messageData);
    }
}
