<?php


namespace Psd2\Domain;


interface TokenRequest
{
    /**
     * TokenRequest constructor.
     * @param UrlsContainer $urls
     * @param string $aspsp
     * @param string $token
     * @param string $clientId
     * @param string $certificate
     */
    public function __construct(UrlsContainer $urls, string $aspsp, string $token, string $clientId, string $certificate);

    /**
     * @param string $code
     * @param string $aspsp
     * @param string $clientId
     * @param string $redirectUri
     * @param string $codeVerifier
     * @return string
     */
    public function getToken(string $code, string $aspsp, string $clientId, string$redirectUri, string $codeVerifier): string;
}
