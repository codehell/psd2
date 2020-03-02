<?php
declare(strict_types=1);

namespace Codehell\Psd2\Application;


use codehell\Psd2\Domain\DomainException\Psd2InitPaymentRejectedException;
use Codehell\Psd2\Domain\PaymentChecker;
use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;

final class CheckPaymentService
{
    private $checker;


    public function __construct(PaymentChecker $checker)
    {
        $this->checker = $checker;
    }

    /**
     * @param string $requestId
     * @param string $token
     * @return string
     * @throws Psd2UrlNotSetException
     * @throws Psd2InitPaymentRejectedException
     */
    public function __invoke(string $requestId, string $token): string
    {
        return $this->checker->checkPayment($requestId, $token);
    }
}
