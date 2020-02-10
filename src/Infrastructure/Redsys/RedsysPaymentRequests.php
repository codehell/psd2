<?php


namespace Psd2\Infrastructure;


use GuzzleHttp\Client;
use Psd2\Domain\PaymentRequests;
use Psd2\Domain\UrlsContainer;

final class RedsysPaymentRequests implements PaymentRequests
{
    const VERSION = 'v1';
    private $client;
    private $aspsp;
    private $clientId;
    private $headers;
    private $certificate;

    /**
     * RedsysPaymentRequests constructor.
     * @param UrlsContainer $urls
     * @param string $aspsp
     * @param string $token
     * @param string $clientId
     * @param string $certificate
     */
    public function __construct(UrlsContainer $urls, string $aspsp, string $token, string $clientId, string $certificate)
    {
        $this->certificate = $certificate;
        $this->client = new Client([
            'base_uri' => $urls->baseUrl()
        ]);
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'TPP-Signature-Certificate' => $certificate,
        ];
        $this->aspsp = $aspsp;
        $this->clientId = $clientId;
    }

    /**
     * {@inheritDoc}
     */
    public function initPayment(
        string $payload,
        string $requestId,
        string $psuIp,
        string $digest,
        string $signature,
        string $redirectUrl
    ): string
    {
        $localHeaders = [
            'X-Request-ID' => $requestId,
            'PSU-IP-Address' => $psuIp,
            'X-IBM-Client-Id' => $this->clientId,
            'PSU-Http-Method' => 'POST',
            'Digest' => $digest,
            'Signature' => $signature,
            'TPP-Redirect-Preferred' => 'true',
            'TPP-Redirect-URI' => $redirectUrl
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $this->client->request('POST', $this->aspsp . '/' . self::VERSION . '/payments/sepa-credit-transfers', [
            'headers' => $headers,
            'body' => $payload,
        ]);
        return $res->getBody()->getContents();
    }

    /**
     * {@inheritDoc}
     */
    public function checkPayment(
        string $requestId,
        string $psuIp,
        string $digest,
        string $signature,
        string $stateUrl
    ): string
    {
        $localHeaders = [
            'X-Request-ID' => $requestId,
            'PSU-IP-Address' => $psuIp,
            'X-IBM-Client-Id' => $this->clientId,
            'PSU-Http-Method' => 'GET',
            'Digest' => $digest,
            'Signature' => $signature,
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $this->client->request('GET', $this->aspsp . $stateUrl, [
            'headers' => $headers,
        ]);
        return $res->getBody()->getContents();
    }
}
