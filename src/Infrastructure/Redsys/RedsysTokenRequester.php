<?php
declare(strict_types=1);
namespace Codehell\Psd2\Infrastructure;

use GuzzleHttp\Client;
use Codehell\Psd2\Domain\TokenRequester;
use Codehell\Psd2\Domain\DomainTraits\SetUrls;
use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Infrastructure\Redsys\Helpers\GuzzleHandlerStackBuilder;

final class RedsysTokenRequester implements TokenRequester
{
    use SetUrls;

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
     * @var array
     */
    private $headers;

    /**
     * RedsysTokenRequest constructor.
     * @param string $aspsp
     * @param string $token
     * @param string $clientId
     * @param string $certificate
     */
    public function __construct(string $aspsp, string $token, string $clientId, string $certificate)
    {
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'TPP-Signature-Certificate' => $certificate,
        ];
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
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $formParams = [
            'grant_type' => 'authorization_code',
            'client_id' => $clientId,
            'code' => $code,
            'redirect_uri' => $redirectUri,
            'code_verifier' => $codeVerifier
        ];

        $url = $this->urls->tokenRequestUrl() . $this->aspsp . '/token';
        $stack = GuzzleHandlerStackBuilder::create();
        $client = new Client(['handler' => $stack]);
        $res = $client->request('POST', $url, [
            'form_params' => $formParams,
            'debug' => false,
        ]);
        $res->getBody()->rewind();
        return $res->getBody()->getContents();
    }
}
