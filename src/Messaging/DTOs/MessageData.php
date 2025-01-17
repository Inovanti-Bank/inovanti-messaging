<?php

namespace InovantiBank\Messaging\DTOs;

class MessageData
{
    public function __construct(
        public string $type,      // Tipo da mensagem (email, sms, whatsapp)
        public string $to,        // Destinatário (e-mail ou telefone)
        public string $from,      // Remetente (opcional, depende do provedor)
        public string $content,   // Conteúdo da mensagem
        public ?array $metadata = [] // Metadados adicionais (headers, configs específicas)
    ) {}
}
