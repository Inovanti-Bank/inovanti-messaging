<?php

namespace InovantiBank\Messaging\DTOs;

use InovantiBank\Messaging\Enums\FileTypesEnum;

class AttachmentData
{
    public function __construct(
        public string $base64_file,
        public string $file_name,
        public FileTypesEnum $file_type = FileTypesEnum::PDF
    ) {
        if (empty($base64_file) || empty($file_name)) {
            throw new \InvalidArgumentException(
                "AttachmentDTO requires 'base64_file' and 'file_name'."
            );
        }

        if (! base64_decode($base64_file, true)) {
            throw new \InvalidArgumentException(
                "The 'base64_file' must be a valid base64 string."
            );
        }
    }
}
