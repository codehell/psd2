<?php
declare(strict_types=1);
namespace Psd2\Infrastructure;

use GuzzleHttp\Client;
use Psd2\Domain\TokenRequest;
use Psd2\Domain\UrlsContainer;

final class RedsysTokenRequest implements TokenRequest
{

    private $client;
    private $aspsp;
    private $clientId;
    private $headers;
    private $certificate;

    /**
     * RedsysTokenRequest constructor.
     * {@inheritDoc}
     */
    public function __construct(UrlsContainer $urls, string $aspsp, string $token, string $clientId, string $certificate)
    {
        $this->certificate = $certificate;
        $this->client = new Client([
            'base_uri' => $urls->tokenRequest()
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
    public function getToken(string $code, string $aspsp, string $clientId, string $redirectUri, string $codeVerifier): string
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
        return $res->getBody()->getContents();
    }
}
