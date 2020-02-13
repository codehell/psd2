<?php
declare(strict_types=1);

namespace Codehell\Psd2\Application;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Domain\PaymentRequester;

final class RequestPaymentService
{
    private $request;

    public function __construct(PaymentRequester $request)
    {
        $this->request = $request;
    }

    /**
     * @param $requestId
     * @param $token
     * @param $key
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function __invoke(): string
    {
        return $this->request->initPayment();
    }
}
