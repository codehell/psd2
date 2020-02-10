<?php
declare(strict_types=1);
namespace Psd2\Infrastructure;

use GuzzleHttp\Client;
use Psd2\Domain\TokenRequest;
use Psd2\Domain\Urls;

final class RedsysTokenRequest implements TokenRequest
{
    /**
     * @var Urls
     */
    private $urls;
    /**
     * @var string
     */
    private $aspsp;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $clientId;
    /**
     * @var string
     */
    private $certificate;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var array
     */
    private $headers;

    /**
     * RedsysTokenRequest constructor.
     * {@inheritDoc}
     */
    public function __construct(Urls $urls, string $aspsp, string $token, string $clientId, string $certificate)
    {
        $this->client = new Client([
            'base_uri' => $urls->tokenRequestUrl()
        ]);
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'TPP-Signature-Certificate' => $certificate,
        ];
        $this->urls = $urls;
        $this->aspsp = $aspsp;
        $this->token = $token;
        $this->clientId = $clientId;
        $this->certificate = $certificate;
    }

    /**
     * {@inheritDoc}
     */
    public function getToken(string $code, string $clientId, string $redirectUri, string $codeVerifier): string
    {
        $url = $this->urls->tokenRequestUrl() . $this->aspsp . '/token';
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
