<?php

namespace InovantiBank\Messaging\Enums;

enum FileTypesEnum: string
{
    case PDF = 'application/pdf';
    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
}