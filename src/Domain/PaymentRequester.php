<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface PaymentRequester extends UrlsSetter
{
    /**
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function initPayment(): string;
}
