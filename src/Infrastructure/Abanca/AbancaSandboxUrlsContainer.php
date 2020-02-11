<?php


namespace Psd2\Infrastructure\Abanca;


use Psd2\Domain\UrlsContainer;

class AbancaSandboxUrlsContainer implements UrlsContainer
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
