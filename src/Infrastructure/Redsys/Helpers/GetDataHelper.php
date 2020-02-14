<?php
declare(strict_types=1);

namespace Codehell\Psd2\Infrastucture\Helpers;


final class GetDataHelper
{
    public static function plainCertificate(string $certificate)
    {
        $result = str_replace('-----BEGIN CERTIFICATE-----', '', $certificate);
        $result = str_replace('-----END CERTIFICATE-----', '', $result);
        $result = str_replace(PHP_EOL, '', $result);
        return $result;
    }
}
