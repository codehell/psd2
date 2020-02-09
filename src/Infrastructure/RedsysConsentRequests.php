<?php

namespace Psd2\Infrastructure;

use GuzzleHttp\Client;
use Psd2\Domain\ConsentRequests;

final class RedsysConsentRequests implements ConsentRequests
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

    /**
     * @param $payload
     * @param $requestId
     * @param $digest
     * @param $signature
     * @param $redirectUrl
     * @return string
     */
    public function initConsent($payload, $requestId, $digest, $signature, $redirectUrl): string
    {
        $localHeaders = [
            'X-Request-ID' => $requestId,
            'X-IBM-Client-Id' => $this->clientId,
            'PSU-Http-Method' => 'POST',
            'TPP-Redirect-Preferred' => 'true',
            'TPP-Redirect-URI' => $redirectUrl,
            'Digest' => $digest,
            'Signature' => $signature,
        ];
        $headers = array_merge($this->headers, $localHeaders);
        // dd($payload);
        $res = $this->client->request('POST', $this->aspsp . '/' . self::VERSION . '/consents', [
            'headers' => $headers,
            'body' => $payload,
        ]);
        return $res->getBody()->getContents();
    }

    /**
     * @param $requestId
     * @param $digest
     * @param $signature
     * @param $consentId
     * @return string
     */
    public function getConsentInfo($requestId, $digest, $signature, $consentId): string
    {
        $localHeaders = [
            'X-Request-ID' => $requestId,
            'X-IBM-Client-Id' => $this->clientId,
            'PSU-Http-Method' => 'GET',
            'Digest' => $digest,
            'Signature' => $signature,
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $this->client->request('GET', $this->aspsp . '/' . self::VERSION . '/consents/' . $consentId, [
            'headers' => $headers,
        ]);
        return $res->getBody()->getContents();
    }
}
