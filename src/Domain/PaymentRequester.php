<?php
declare(strict_types=1);

namespace Psd2\Domain;


use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface PaymentRequester extends UrlsSetter
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
