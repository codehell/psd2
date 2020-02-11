<?php


namespace Psd2\Domain;


use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface PaymentRequest extends SetUrls
{
    /**
     * @param string $requestId
     * @param string $token
     * @param string $key
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function initPayment(string $requestId, string $token, string $key): string;
}
