<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface PaymentChecker extends UrlsSetter
{
    /**
     * @param string $requestId
     * @param string $token
     * @param string $clientId
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function checkPayment(string $requestId, string $token, string $clientId): string;
}
