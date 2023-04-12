<?php

namespace App\Shared\Helpers;

use App\Features\Module\Modules\Infra\Models\Module;
use Carbon\Carbon;

class Helpers
{
    public static function onlyNumbers(string $value = null): string|null
    {
        return isset($value) && !empty($value)
            ? preg_replace('/\D/is', '', $value)
            : null;
    }

    public static function stringUniqueName($string = null): array|string|null
    {
        $string = strtolower(self::removeAccents($string));

        return preg_replace('/[\s-]+/' , '-' , $string);
    }

    public static function removeAccents($string = null) {
        $search = array('À', 'Á', 'Ã', 'Â', 'É', 'Ê', 'Í', 'Ó', 'Õ', 'Ô', 'Ú', 'Ü', 'Ç', 'à', 'á', 'ã', 'â', 'é', 'ê', 'í', 'ó', 'õ', 'ô', 'ú', 'ü', 'ç');
        $replace = array('a', 'a', 'a', 'a', 'e', 'r', 'i', 'o', 'o', 'o', 'u', 'u', 'c', 'a', 'a', 'a', 'a', 'e', 'e', 'i', 'o', 'o', 'o', 'u', 'u', 'c');
        return str_replace($search, $replace, $string);
    }

    public static function getCurrentTimestampCarbon(): Carbon
    {
        return Carbon::now()->timezone('America/Sao_Paulo');
    }

    public static function getApiUrl(string $element = null): string
    {
        return config('general.app_url')."/{$element}";
    }

    public static function getAppWebUrl(string $element = null): string
    {
        return config('general.app_web_url')."/{$element}";
    }
}
