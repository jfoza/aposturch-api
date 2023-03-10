<?php

namespace App\Shared\Libraries;

use Brazanation\Documents\{AbstractDocument, Cnpj, Cpf};

abstract class ValidationDocuments
{
    /**
     * @param string $doc
     * @return AbstractDocument|Cnpj|bool|string
     */
    public static function checkCNPJ(string $doc): AbstractDocument|Cnpj|bool|string
    {
        return Cnpj::createFromString($doc);
    }

    /**
     * @param string $doc
     * @return AbstractDocument|Cnpj|bool|string
     */
    public static function checkCPF(string $doc): AbstractDocument|Cnpj|bool|string
    {
        return Cpf::createFromString($doc);
    }

    /**
     * @param string $doc
     * @return string
     */
    public static function formatCNPJ(string $doc): string
    {
        $doc = Cnpj::createFromString($doc);

        return $doc->format();
    }

    /**
     * @param string $doc
     * @return string
     */
    public static function formatCPF(string $doc): string
    {
        $doc = Cpf::createFromString($doc);

        return $doc->format();
    }
}
