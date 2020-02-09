<?php


namespace Psd2\Infrastructure;


use GuzzleHttp\Client;
use Psd2\Domain\TokenRequest;

final class RedsysTokenRequest implements TokenRequest
{

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
     * @param $code
     * @param $aspsp
     * @param $clientId
     * @param $redirectUri
     * @param $codeVerifier
     * @return string
     */
    public function getToken($code, $aspsp, $clientId, $redirectUri, $codeVerifier): string
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
