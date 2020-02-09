<?php


namespace Psd2\Domain;


interface ConsentRequests
{
    public function initConsent($payload, $requestId, $digest, $signature, $redirectUrl): string;
    public function getConsentInfo($requestId, $digest, $signature, $consentId): string;
}
