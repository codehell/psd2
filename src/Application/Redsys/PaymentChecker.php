<?php


namespace Psd2\Application\Redsys;


use Psd2\Domain\PaymentRequests;

final class PaymentChecker
{
    /**
     * @var PaymentRequests
     */
    private $requests;

    /**
     * PaymentChecker constructor.
     * @param PaymentRequests $requests
     */
    public function __construct(PaymentRequests $requests)
    {
        $this->requests = $requests;
    }

    public function __invoke(string $requestId, string $psuIp, string $digest, string $signature, string $stateUrl): string
    {
        return $this->requests->checkPayment($requestId, $psuIp, $digest, $signature, $stateUrl);
    }
}
