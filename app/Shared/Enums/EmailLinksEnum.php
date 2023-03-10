<?php

namespace App\Shared\Enums;

use App\Shared\Helpers\Helpers;

enum EmailLinksEnum
{
    public static function getLinks(): array
    {
        return [
            'logo'           => Helpers::getApiUrl('storage/email/lalak-logo-email.png'),
            'facebookLogo'   => Helpers::getApiUrl('storage/email/facebook.png'),
            'instagramLogo'  => Helpers::getApiUrl('storage/email/instagram.png'),
            'pinterestLogo'  => Helpers::getApiUrl('storage/email/pinterest.png'),
            'facebook'       => 'https://www.facebook.com/lalakdoceriaartesanal',
            'instagram'      => 'https://www.instagram.com/lalakdoceriaartesanal',
            'pinterest'      => 'https://br.pinterest.com/contato7332',
            'whatsApp'       => 'https://wa.me/5551998701147',
            'support'        => Helpers::getAppWebUrl('ajuda-e-suporte'),
        ];
    }
}
