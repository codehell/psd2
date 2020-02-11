<?php
declare(strict_types=1);

namespace Psd2\Domain;


use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface PaymentChecker extends UrlsSetter
{
    /**
     * @param string $requestId
     * @param string $token
     * @param string $key
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function checkPayment(string $requestId, string $token, string $key): string;
}
