<?php


namespace Psd2\Infrastructure\Abanca;


use Psd2\Domain\Urls;

class AbancaSandboxUrls implements Urls
{
    public function tokenRequestUrl(): string
    {
        return "https://api.abanca.com/oauth/{id cliente}/Sandbox";
    }

    public function baseUrl(): string
    {
        return "https://api.abanca.com/sandbox";
    }
}
