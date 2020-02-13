<?php
declare(strict_types=1);
namespace Codehell\Psd2\Infrastructure\Redsys;

use GuzzleHttp\Client;
use Codehell\Psd2\Domain\StringInvoker;

final class RedsysAspsps implements StringInvoker
{
    private $requestId;
    private $digest;
    private $signature;
    private $certificate;

    /**
     * RedsysAspsps constructor.
     * @param $requestId
     * @param $digest
     * @param $signature
     * @param $certificate
     */
    public function __construct($requestId, $digest, $signature, $certificate)
    {
        $this->requestId = $requestId;
        $this->digest = $digest;
        $this->signature = $signature;
        $this->certificate = $certificate;
    }

    /**
     * @return string
     */
    public function __invoke(): string
    {
        $client = new Client([
            'base_uri' => "https://apis-i.redsys.es:20443/psd2/xs2a/api-entrada-xs2a/services/"
        ]);
        $headers = [
            'accept' => 'application/json',
            'X-Request-ID' => $this->requestId,
            'Digest' => $this->digest,
            'Signature' => $this->signature,
            'TPP-Signature-Certificate' => $this->certificate,
        ];
        $res = $client->request('GET', 'v2/sva/aspsps', [
            'headers' => $headers,
        ]);
        return $res->getBody()->getContents();
    }
}
