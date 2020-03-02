<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use codehell\Psd2\Domain\DomainException\Psd2InitPaymentRejectedException;

interface PaymentChecker extends UrlsSetter
{
    /**
     * @param string $requestId
     * @param string $token
     * @return string
     * @throws Psd2UrlNotSetException
     * @throws Psd2InitPaymentRejectedException
     */
    public function checkPayment(string $requestId, string $token): string;
}
