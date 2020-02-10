<?php


namespace Psd2\Infrastructure\Redsys;


use Psd2\Domain\UrlsContainer;

class RedsysSandboxUrlsContainer implements UrlsContainer
{

    public function tokenRequest(): string
    {
        return 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/';
    }

    public function baseUrl(): string
    {
        return "https://apis-i.redsys.es:20443/psd2/xs2a/api-entrada-xs2a/services/";
    }
}
