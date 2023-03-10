<?php

namespace App\Shared\Helpers;

use App\Shared\Libraries\ValidationDocuments;

class ValidationDocsHelper
{
    public static function validateCPF(string $doc): bool
    {
        if(!ValidationDocuments::checkCPF($doc)) {
            return false;
        }

        return true;
    }

    public static function validateCNPJ(string $doc): bool
    {
        if(!ValidationDocuments::checkCNPJ($doc)) {
            return false;
        }

        return true;
    }

    public static function formatCPF(string $doc): string
    {
        return ValidationDocuments::formatCPF($doc);
    }

    public static function formatCNPJ(string $doc): string
    {
        return ValidationDocuments::formatCNPJ($doc);
    }
}
