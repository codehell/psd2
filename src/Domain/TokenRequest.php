<?php


namespace Psd2\Domain;


use App\Domain\DomainException\Psd2UrlNotSetException;

interface TokenRequest
{
    public function setUrls(Urls $urls);

    /**
     * @param string $code
     * @param string $clientId
     * @param string $redirectUri
     * @param string $codeVerifier
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function getToken(string $code, string $clientId, string$redirectUri, string $codeVerifier): string;
}
