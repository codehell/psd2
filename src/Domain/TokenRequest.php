<?php


namespace Psd2\Domain;


interface TokenRequest
{
    public function getToken($code, $aspsp, $clientId, $redirectUri, $codeVerifier): string;
}
