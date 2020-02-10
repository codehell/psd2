<?php

namespace Psd2\Infrastructure;

use GuzzleHttp\Client;
use Psd2\Domain\ConsentRequests;
use Psd2\Domain\Urls;

final class RedsysConsentRequests implements ConsentRequests
{
    const VERSION = 'v1';
    private $client;
    private $aspsp;
    private $clientId;
    private $headers;
    private $certificate;
    private $token;

    /**
     * RedsysConsentRequests constructor.
     * {@inheritDoc}
     */
    public function __construct(Urls $urls, string $aspsp, string $token, string $clientId, string $certificate)
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
        $this->token = $token;
    }

    /**
     * {@inheritDoc}
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
     * {@inheritDoc}
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
