<?php


namespace Codehell\Psd2\Infrastructure\Redsys;


use GuzzleHttp\Client;
use Codehell\Psd2\Domain\PaymentChecker;
use Codehell\Psd2\Domain\DomainTraits\SetUrls;
use Codehell\Psd2\Infrastucture\Helpers\GetDataHelper;
use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Domain\DomainException\Psd2InitPaymentRejectedException;
use Codehell\Psd2\Infrastructure\Redsys\Helpers\GuzzleHandlerStackBuilder;

final class RedsysPaymentChecker implements PaymentChecker
{
    use SetUrls;

    private const PAYMENT_STATUS_ACCEPTED = 'ACSC';
    // TODO: construir esta url a partir del id: 59e5579c-1490-41af-ac92-07af57a057bc (meter en el contenedor de urls)
    private const STATE_URL = '/v1/payments/sepa-credit-transfers/59e5579c-1490-41af-ac92-07af57a057bc/status';

    private $aspsp;
    private $headers;
    private $redirectUrl;
    private $version;
    private $stateUrl;
    /**
     * @var string
     */
    private $clientId;

    /**
     * RedsysPaymentRequests constructor.
     * @param string $aspsp
     * @param string $digest
     * @param string $certificate
     * @param string $headerSignature
     * @param string $version
     * @param string $redirectUrl
     * @param string $psuIp
     * @param string $stateUrl
     * @param string $clientId
     */
    public function __construct(
        string $aspsp,
        string $digest,
        string $certificate,
        string $headerSignature,
        string $version,
        string $redirectUrl,
        string $psuIp,
        string $stateUrl,
        string $clientId
    )
    {
        $plainCertificate = GetDataHelper::plainCertificate($certificate);
        $this->headers = [
            'accept' => 'application/json',
            'content-type' => 'application/json',
            'TPP-Signature-Certificate' => $plainCertificate,
            'PSU-IP-Address' => $psuIp,
            'Digest' => $digest,
            'Signature' => $headerSignature,
        ];
        $this->aspsp = $aspsp;
        $this->version = $version;
        $this->redirectUrl = $redirectUrl;
        $this->stateUrl = $stateUrl;
        $this->clientId = $clientId;
    }

    /**
     * {@inheritDoc}
     */
    public function checkPayment(string $requestId, string $token): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $stack = GuzzleHandlerStackBuilder::create();

        $client = new Client([
            'handler' => $stack,
            'base_uri' => $this->urls->baseUrl()
        ]);

        $localHeaders = [
            'Authorization' => 'Bearer ' . $token,
            'X-Request-ID' => $requestId,
            'X-IBM-Client-Id' => $this->clientId,
            'PSU-Http-Method' => 'GET'
        ];
        $headers = array_merge($this->headers, $localHeaders);
        $res = $client->request('GET', $this->aspsp . $this->stateUrl, [
            'headers' => $headers,
        ]);
        $res->getBody()->rewind();
        $response = $res->getBody()->getContents();
        $arrayResponse = json_decode($response, true);

        if ($arrayResponse['transactionStatus'] !== self::PAYMENT_STATUS_ACCEPTED) {
            throw new Psd2InitPaymentRejectedException();
        }

        return $response;
    }
}
