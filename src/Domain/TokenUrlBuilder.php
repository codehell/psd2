<?php


namespace Psd2\Domain;


interface TokenUrlBuilder
{
    public function __construct(
        Urls $urls,
        string $aspsp,
        string $clientId,
        string $codeChallenge,
        string $state,
        string $redirectUri,
        string $method = 'plain'
    );
    public function __invoke(): string;
}
