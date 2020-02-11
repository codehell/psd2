<?php


namespace Psd2\Infrastructure\Redsys;


use GuzzleHttp\Client;
use Psd2\Domain\PaymentChecker;
use Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Psd2\Domain\DomainTraits\SetUrls;

final class RedsysPaymentChecker implements PaymentChecker
{
    use SetUrls;

    private $aspsp;
    private $headers;
    private $redirectUrl;
    private $version;
    private $stateUrl;

    /**
     * RedsysPaymentRequests constructor.
     * @param string $aspsp
     * @param string $digest
     * @param string $certificate
     * @param string $signature
     * @param string $version
     * @param string $redirectUrl
     * @param string $psuIp
     * @param string $stateUrl
     */
    public function __construct(
        string $aspsp,
        string $digest,
        string $certificate,
        string $signature,
        string $version,
        string $redirectUrl,
        string $psuIp,
        string $stateUrl
    )
    {
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'TPP-Signature-Certificate' => $certificate,
            'PSU-IP-Address' => $psuIp,
            'Digest' => $digest,
            'Signature' => $signature,
        ];
        $this->aspsp = $aspsp;
        $this->version = $version;
        $this->redirectUrl = $redirectUrl;
        $this->stateUrl = $stateUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function checkPayment(string $requestId, string $token, string $key): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $client = new Client([
            'base_uri' => $this->urls->baseUrl()
        ]);

        $localHeaders = [
            'X-Request-ID' => $requestId,
            'X-IBM-Client-Id' => $key,
            'PSU-Http-Method' => 'GET'
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $client->request('GET', $this->aspsp . $this->stateUrl, [
            'headers' => $headers,
        ]);
        return $res->getBody()->getContents();
    }
}
