<?php


namespace Psd2\Application\Redsys;


use Psd2\Domain\PaymentRequests;

final class PaymentInitializer
{
    /**
     * @var PaymentRequests
     */
    private $request;

    public function __construct(PaymentRequests $request)
    {
        $this->request = $request;
    }

    public function __invoke(
        string $payload,
        string $requestId,
        string $psuIp,
        string $digest,
        string $signature,
        string $redirectUrl
    ): string
    {
        return $this->request->initPayment($payload, $requestId, $psuIp, $digest, $signature, $redirectUrl);
    }
}
