<?php


namespace Psd2\Domain;


interface PaymentRequests
{
    public function __construct(Urls $urls, string $aspsp, string $token, string $clientId, string $certificate);

    /**
     * @param string $payload
     * @param string $requestId
     * @param string $psuIp
     * @param string $digest
     * @param string $signature
     * @param string $redirectUrl
     * @return string
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
     */
    public function checkPayment(
        string $requestId,
        string $psuIp,
        string $digest,
        string $signature,
        string $stateUrl
    ): string;
}
