<?php

namespace InovantiBank\Messaging\DTOs;

class MessageData
{
    /**
     * @param AttachmentData[] $addAttachments
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
    ) {
        foreach ($this->addAttachments as $attachment) {
            if (! $attachment instanceof AttachmentData) {
                throw new \InvalidArgumentException(
                    "Attachments should be instances of AttachmentData."
                );
            }
        }
    }
}
