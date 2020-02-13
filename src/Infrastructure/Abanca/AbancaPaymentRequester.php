<?php


namespace Codehell\Psd2\Infrastructure\Abanca;


use GuzzleHttp\Client;
use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Domain\DomainTraits\SetUrls;
use Codehell\Psd2\Domain\PaymentRequester;

class AbancaPaymentRequester implements PaymentRequester
{
    use SetUrls;
    /**
     * @inheritDoc
     */
    public function initPayment(string $requestId, string $token, string $key): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $client = new Client([
            'base_uri' => $this->urls->baseUrl()
        ]);
        // TODO: Implement initPayment() method.
        return '';
    }
}
