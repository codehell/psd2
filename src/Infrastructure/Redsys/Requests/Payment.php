<?php


namespace Codehell\Psd2\Infrastructure\Redsys\Requests;


use Codehell\Psd2\Domain\Signer;

class Payment
{
    /**
     * @var string
     */
    private $provider;
    /**
     * @var string
     */
    private $aspsp;
    /**
     * @var string
     */
    private $paymentProduct;
    /**
     * @var string
     */
    private $requestId;
    /**
     * @var string
     */
    private $token;
    /**
     * @var string
     */
    private $psuIp;
    /**
     * @var string
     */
    private $redirectUri;
    /**
     * @var string
     */
    private $certificate;
    /**
     * @var string
     */
    private $amount;
    /**
     * @var string
     */
    private $creditorAccount;
    /**
     * @var string
     */
    private $creditorName;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var Signer
     */
    private $signer;
    /**
     * @var string
     */
    private $privateKey;
    /**
     * @var string
     */
    private $digest;
    /**
     * @var string
     */
    private $payload;
    /**
     * @var string
     */
    private $sha256Digest;
    /**
     * @var string
     */
    private $signature;
    /**
     * @var string
     */
    private $headerSignature;
    /**
     * @var string
     */
    private $version;
    /**
     * @var string
     */
    private $clientId;

    public function __construct(
        string $provider,
        string $aspsp,
        string $paymentProduct,
        string $requestId,
        string $token,
        string $psuIp,
        string $redirectUri,
        string $certificate,
        string $amount,
        string $creditorAccount,
        string $creditorName,
        string $privateKey,
        string $clientId,
        string $version = 'v1',
        string $currency = 'EUR'
    )
    {
        $this->provider = $provider;
        $this->aspsp = $aspsp;
        $this->paymentProduct = $paymentProduct;
        $this->requestId = $requestId;
        $this->token = $token;
        $this->psuIp = $psuIp;
        $this->redirectUri = $redirectUri;
        $this->certificate = $certificate;
        $this->amount = $amount;
        $this->creditorAccount = $creditorAccount;
        $this->creditorName = $creditorName;
        $this->currency = $currency;
        $this->signer = new Signer;
        $this->privateKey = $privateKey;
        // this code must execute in order
        $this->generatePayload();
        $this->generateDigest();
        $this->generateSHA256Digest();
        $this->generateSignature();
        $this->generateHeaderSignature();
        // end of sequential code
        $this->version = $version;
        $this->clientId = $clientId;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @return string
     */
    public function getAspsp(): string
    {
        return $this->aspsp;
    }

    /**
     * @return string
     */
    public function getRequestId(): string
    {
        return $this->requestId;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @return string
     */
    public function getPsuIp(): string
    {
        return $this->psuIp;
    }

    /**
     * @return string
     */
    public function getCertificate(): string
    {
        $result = str_replace('-----BEGIN CERTIFICATE-----', '', $this->certificate);
        $result = str_replace('-----END CERTIFICATE-----', '', $result);
        $result = str_replace(PHP_EOL, '', $result);
        return $result;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }

    /**
     * @return string
     */
    public function getSha256Digest(): string
    {
        return $this->sha256Digest;
    }

    public function generatePayload(): void
    {
        $payload = [
            'instructedAmount' => [
                'currency' => $this->currency,
                'amount' => $this->amount
            ],
            'creditorAccount' => [
                'iban' => $this->creditorAccount
            ],
            'creditorName' => $this->creditorName
        ];
        $this->payload = json_encode($payload);
    }

    /**
     * @return string
     */
    public function getHeaderSignature(): string
    {
        return $this->headerSignature;
    }

    private function generateDigest(): void
    {
        $this->digest = $this->signer->getDigest($this->payload);
    }

    private function generateSHA256Digest(): void
    {
        $this->sha256Digest = $this->signer->getSHA256Digest($this->payload);
    }

    private function generateSignature(): void
    {
        $this->signature = $this->signer->getSignature(
            $this->sha256Digest,
            $this->requestId,
            $this->privateKey
        );
    }

    private function generateHeaderSignature(): void
    {
        $this->headerSignature = $this->signer->headerSignature($this->signature, $this->certificate);
    }
}