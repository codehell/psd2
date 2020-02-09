<?php


namespace Psd2\Infrastructure;


use GuzzleHttp\Client;
use Psd2\Domain\PaymentRequests;

final class RedsysPaymentRequests implements PaymentRequests
{
    const VERSION = 'v1';
    private $client;
    private $aspsp;
    private $clientId;
    private $headers;
    private $certificate;

    /**
     * Requests constructor.
     * @param $aspsp
     * @param $token
     * @param $clientId
     * @param $certificate
     */
    public function __construct($aspsp, $token, $clientId, $certificate)
    {
        $this->certificate = $certificate;
        $this->client = new Client([
            'base_uri' => "https://apis-i.redsys.es:20443/psd2/xs2a/api-entrada-xs2a/services/"
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

    public function initPayment($payload, $requestId, $psuIp, $digest, $signature, $redirectUrl): string
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

    public function checkPayment($requestId, $psuIp, $digest, $signature, $stateUrl): string
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
