<?php


namespace Psd2\Infrastructure\Redsys;


use Psd2\Domain\Urls;

final class RedsysSandboxUrls implements Urls
{

    public function tokenRequestUrl(): string
    {
        return 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/';
    }

    public function baseUrl(): string
    {
        return "https://apis-i.redsys.es:20443/psd2/xs2a/api-entrada-xs2a/services/";
    }
}
