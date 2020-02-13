<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface TokenRequester extends UrlsSetter
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
