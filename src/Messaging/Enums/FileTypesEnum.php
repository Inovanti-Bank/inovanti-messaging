<?php

namespace InovantiBank\Messaging\Enums;

enum FileTypesEnum: string
{
    case PDF = 'application/pdf';
    case DOC = 'application/msword';
    case DOCX = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
    case TXT = 'text/plain';
    case RTF = 'application/rtf';

    case CSV = 'text/csv';
    case XLS = 'application/vnd.ms-excel';
    case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';
    case ODS = 'application/vnd.oasis.opendocument.spreadsheet';

    case PPT = 'application/vnd.ms-powerpoint';
    case PPTX = 'application/vnd.openxmlformats-officedocument.presentationml.presentation';

    case JPEG = 'image/jpeg';
    case PNG = 'image/png';
    case GIF = 'image/gif';
    case SVG = 'image/svg+xml';
    case WEBP = 'image/webp';

    case ZIP = 'application/zip';
    case RAR = 'application/vnd.rar';
    case TAR = 'application/x-tar';
    case GZ = 'application/gzip';
    
    case JSON = 'application/json';
    case XML = 'application/xml';
}