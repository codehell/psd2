<?php


namespace Psd2\Domain;



use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface TokenRequest extends SetUrls
{
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
