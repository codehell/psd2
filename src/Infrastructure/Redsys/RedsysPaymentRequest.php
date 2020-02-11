<?php


namespace Psd2\Infrastructure;


use GuzzleHttp\Client;
use Psd2\Domain\PaymentRequest;
use Psd2\Domain\DomainTraits\SetUrls;
use Psd2\Domain\DomainException\Psd2UrlNotSetException;

final class RedsysPaymentRequest implements PaymentRequest
{
    use SetUrls;

    private $aspsp;
    private $headers;
    private $version;
    private $payload;

    /**
     * RedsysPaymentRequests constructor.
     * @param string $aspsp
     * @param string $payload
     * @param string $digest
     * @param string $certificate
     * @param string $signature
     * @param string $version
     * @param string $redirectUrl
     * @param string $psuIp
     */
    public function __construct(
        string $aspsp,
        string $payload,
        string $digest,
        string $certificate,
        string $signature,
        string $version,
        string $redirectUrl,
        string $psuIp
    )
    {
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'TPP-Signature-Certificate' => $certificate,
            'PSU-IP-Address' => $psuIp,
            'Digest' => $digest,
            'Signature' => $signature,
            'TPP-Redirect-URI' => $redirectUrl
        ];
        $this->aspsp = $aspsp;
        $this->version = $version;
        $this->payload = $payload;
    }

    /**
     * The key is clientId
     * {@inheritDoc}
     */
    public function initPayment(string $requestId, string $token, string $key): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $client = new Client([
            'base_uri' => $this->urls->baseUrl()
        ]);

        $localHeaders = [
            'Authorization' => 'Bearer ' . $token,
            'X-Request-ID' => $requestId,
            'X-IBM-Client-Id' => $key,
            'PSU-Http-Method' => 'POST',
            'TPP-Redirect-Preferred' => 'true',
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $client->request('POST', $this->aspsp . '/' . $this->version . '/payments/sepa-credit-transfers', [
            'headers' => $headers,
            'body' => $this->payload,
        ]);
        return $res->getBody()->getContents();
    }
}
