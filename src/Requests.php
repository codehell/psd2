<?php
declare(strict_types=1);

namespace Psd2;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

final class Requests
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
     * @param $requestId
     * @param $digest
     * @param $signature
     * @return StreamInterface
     */
    public function getBanks($requestId, $digest, $signature): StreamInterface
    {
        $headers = [
            'accept' => 'application/json',
            'X-Request-ID' => $requestId,
            'Digest' => $digest,
            'Signature' => $signature,
            'TPP-Signature-Certificate' => $this->certificate,
        ];
        $res = $this->client->request('GET', 'v2/sva/aspsps', [
            'headers' => $headers,
        ]);
        return $res->getBody();
    }

    /**
     * @param $code
     * @param $aspsp
     * @param $clientId
     * @param $redirectUri
     * @param $codeVerifier
     * @return StreamInterface
     */
    public function getToken($code, $aspsp, $clientId, $redirectUri, $codeVerifier): StreamInterface
    {
        // TODO Put address in config file.
        $url = 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/' . $aspsp . '/token';
        $client = new Client();
        $res = $client->request('POST', $url, [
            'form_params' => [
                'grant_type' => 'authorization_code',
                'client_id' => $clientId,
                'code' => $code,
                'redirect_uri' => $redirectUri,
                'code_verifier' => $codeVerifier
            ],
            'debug' => false,
        ]);
        return $res->getBody();
    }

    /**
     * @param $payload
     * @param $requestId
     * @param $psuIp
     * @param $digest
     * @param $signature
     * @param $redirectUrl
     * @return StreamInterface
     */
    public function initPayment($payload, $requestId, $psuIp, $digest, $signature, $redirectUrl): StreamInterface
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
        return $res->getBody();
    }

    /**
     * @param $requestId
     * @param $psuIp
     * @param $digest
     * @param $signature
     * @param $stateUrl
     * @return StreamInterface
     */
    public function checkPayment($requestId, $psuIp, $digest, $signature, $stateUrl): StreamInterface
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
        return $res->getBody();
    }

    /**
     * @param $payload
     * @param $requestId
     * @param $digest
     * @param $signature
     * @param $redirectUrl
     * @return StreamInterface
     */
    public function initConsent($payload, $requestId, $digest, $signature, $redirectUrl): StreamInterface
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
        return $res->getBody();
    }

    /**
     * @param $requestId
     * @param $digest
     * @param $signature
     * @param $consentId
     * @return StreamInterface
     */
    public function getConsentInfo($requestId, $digest, $signature, $consentId): StreamInterface
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
        return $res->getBody();
    }
}