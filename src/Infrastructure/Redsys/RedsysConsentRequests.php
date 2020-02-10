<?php

namespace Psd2\Infrastructure;

use App\Domain\DomainException\Psd2UrlNotSetException;
use GuzzleHttp\Client;
use Psd2\Domain\ConsentRequests;
use Psd2\Domain\Urls;

final class RedsysConsentRequests implements ConsentRequests
{
    /**
     * @var string
     */
    private $aspsp;
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var array
     */
    private $headers;
    /**
     * @var string
     */
    private $certificate;
    /**
     * @var string
     */
    private $token;
    /**
     * @var Urls
     */
    private $urls;
    /**
     * @var string
     */
    private $version;

    /**
     * RedsysConsentRequests constructor.
     * {@inheritDoc}
     */
    public function __construct(string $aspsp, string $token, string $clientId, string $certificate, string $version = 'v1')
    {
        $this->certificate = $certificate;
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'TPP-Signature-Certificate' => $certificate,
        ];
        $this->aspsp = $aspsp;
        $this->clientId = $clientId;
        $this->token = $token;
        $this->version = $version;
    }

    public function setUrls(Urls $urls)
    {
        $this->urls = $urls;
    }

    /**
     * {@inheritDoc}
     */
    public function initConsent($payload, $requestId, $digest, $signature, $redirectUrl): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $client = new Client([
            'base_uri' => $this->urls->baseUrl()
        ]);

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

        $res = $client->request('POST', $this->aspsp . '/' . $this->version . '/consents', [
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
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $client = new Client([
            'base_uri' => $this->urls->baseUrl()
        ]);

        $localHeaders = [
            'X-Request-ID' => $requestId,
            'X-IBM-Client-Id' => $this->clientId,
            'PSU-Http-Method' => 'GET',
            'Digest' => $digest,
            'Signature' => $signature,
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $client->request('GET', $this->aspsp . '/' . $this->version . '/consents/' . $consentId, [
            'headers' => $headers,
        ]);
        return $res->getBody()->getContents();
    }
}
