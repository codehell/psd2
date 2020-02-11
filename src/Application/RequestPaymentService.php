<?php
declare(strict_types=1);

namespace Psd2\Application;


use Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Psd2\Domain\PaymentRequester;

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
