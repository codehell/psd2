<?php


namespace Codehell\Psd2\Infrastructure\Redsys;


use GuzzleHttp\Client;
use Codehell\Psd2\Domain\PaymentRequester;
use Codehell\Psd2\Domain\DomainTraits\SetUrls;
use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Infrastructure\Redsys\Requests\Payment;

final class RedsysPaymentRequester implements PaymentRequester
{
    use SetUrls;

    private $payment;

    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * The key is clientId
     * {@inheritDoc}
     * @throws Psd2UrlNotSetException
     */
    public function initPayment(): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $client = new Client([
            'base_uri' => $this->urls->baseUrl()
        ]);

        $headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'TPP-Signature-Certificate' => $this->payment->getCertificate(),
            'PSU-IP-Address' => $this->payment->getPsuIp(),
            'Digest' => $this->payment->getSha256Digest(),
            'Signature' => $this->payment->getHeaderSignature(),
            'TPP-Redirect-URI' => $this->payment->getRedirectUri(),
            'Authorization' => 'Bearer ' . $this->payment->getToken(),
            'X-Request-ID' => $this->payment->getRequestId(),
            'X-IBM-Client-Id' => $this->payment->getClientId(),
            'PSU-Http-Method' => 'POST',
            'TPP-Redirect-Preferred' => 'true',
        ];
        $res = $client->request(
            'POST',
            $this->payment->getAspsp() . '/' . $this->payment->getVersion() . '/sva/payments/sepa-credit-transfers',
            [
                'headers' => $headers,
                'body' => $this->payment->getPayload(),
                'debug' => true
            ]
        );
        return $res->getBody()->getContents();
    }
}
