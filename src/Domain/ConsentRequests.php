<?php


namespace Psd2\Domain;


interface ConsentRequests
{
    /**
     * ConsentRequests constructor.
     * @param UrlsContainer $urls
     * @param string $aspsp
     * @param string $token
     * @param string $clientId
     * @param string $certificate
     */
    public function __construct(UrlsContainer $urls, string $aspsp, string $token, string $clientId, string $certificate);

    /**
     * @param string $payload
     * @param string $requestId
     * @param string $digest
     * @param string $signature
     * @param string $redirectUrl
     * @return string
     */
    public function initConsent(string $payload, string $requestId, string $digest, string $signature, string $redirectUrl): string;

    /**
     * @param string $requestId
     * @param string $digest
     * @param string $signature
     * @param string $consentId
     * @return string
     */
    public function getConsentInfo(string $requestId, string $digest, string $signature, string $consentId): string;
}
