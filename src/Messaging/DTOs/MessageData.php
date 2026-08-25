<?php

namespace InovantiBank\Messaging\DTOs;

class MessageData
{
    /**
     * @param  AttachmentData[]  $addAttachments
     */
    public function __construct(
        public string $type,      // Tipo da mensagem (email, sms, whatsapp)
        public string $to,        // Destinatário (e-mail ou telefone)
        public string $from,      // Remetente (opcional, depende do provedor)
        public string $content,   // Conteúdo da mensagem
        public ?array $metadata = [], // Metadados adicionais (headers, configs específicas)
        public ?array $addCC = [], // Destinatários em cópia visível (e-mail ou telefone)
        public ?array $addBCC = [], // Destinatários em cópia oculta (e-mail ou telefone)
        public ?array $addAttachments = [], // Anexos da mensagem
        public ?string $subject = null, // Assunto estruturado do e-mail
        public ?string $fromName = null, // Nome do remetente
        public ?string $replyTo = null, // Endereço para respostas
        public ?string $replyToName = null, // Nome do destinatário de respostas
        public ?array $categories = [], // Categorias gerais do SendGrid
        public ?array $customArgs = [], // Argumentos rastreáveis do SendGrid
        public ?array $headers = [], // Cabeçalhos customizados do SendGrid
        public ?string $correlationId = null, // Identificador de correlação da aplicação
    ) {
        foreach ($this->addAttachments as $attachment) {
            if (! $attachment instanceof AttachmentData) {
                throw new \InvalidArgumentException(
                    'Attachments should be instances of AttachmentData.'
                );
            }
        }
    }
}
