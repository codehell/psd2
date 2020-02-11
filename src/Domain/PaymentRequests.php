<?php


namespace Psd2\Domain;


use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface PaymentRequests extends SetUrls
{
    /**
     * @param string $payload
     * @param string $requestId
     * @param string $psuIp
     * @param string $digest
     * @param string $signature
     * @param string $redirectUrl
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function initPayment(
        string $payload,
        string $requestId,
        string $psuIp,
        string $digest,
        string $signature,
        string $redirectUrl
    ): string;

    /**
     * @param string $requestId
     * @param string $psuIp
     * @param string $digest
     * @param string $signature
     * @param string $stateUrl
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function checkPayment(
        string $requestId,
        string $psuIp,
        string $digest,
        string $signature,
        string $stateUrl
    ): string;
}
