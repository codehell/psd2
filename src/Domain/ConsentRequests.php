<?php


namespace Psd2\Domain;


use App\Domain\DomainException\Psd2UrlNotSetException;

interface ConsentRequests
{
    /**
     * @param Urls $urls
     * @return mixed
     */
    public function setUrls(Urls $urls);

    /**
     * @param string $payload
     * @param string $requestId
     * @param string $digest
     * @param string $signature
     * @param string $redirectUrl
     * @throws Psd2UrlNotSetException
     * @return string
     */
    public function initConsent(string $payload, string $requestId, string $digest, string $signature, string $redirectUrl): string;

    /**
     * @param string $requestId
     * @param string $digest
     * @param string $signature
     * @param string $consentId
     * @throws Psd2UrlNotSetException
     * @return string
     */
    public function getConsentInfo(string $requestId, string $digest, string $signature, string $consentId): string;
}
