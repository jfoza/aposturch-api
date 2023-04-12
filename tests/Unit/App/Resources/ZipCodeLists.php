<?php

namespace Tests\Unit\App\Resources;

class ZipCodeLists
{
    public static function getAddressByZipCode(): object
    {
        return (object) ([
            'cep'         => '93052-170',
            'logradouro'  => 'Rua Otto Daudt',
            'complemento' => "",
            'bairro'      => 'Feitoria',
            'localidade'  => 'São Leopoldo',
            'uf'          => 'RS',
            'ibge'        => '4318705',
            'gia'         => "",
            'ddd'         => '51',
            'siafi'       => '8877',
        ]);
    }
}
