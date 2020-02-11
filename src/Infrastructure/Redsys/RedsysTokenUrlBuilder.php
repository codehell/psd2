<?php
declare(strict_types=1);

namespace Psd2\Infrastructure\Redsys;


use Psd2\Domain\TokenUrlBuilder;
use Psd2\Domain\DomainTraits\SetUrls;
use Psd2\Domain\DomainException\Psd2UrlNotSetException;

final class RedsysTokenUrlBuilder implements TokenUrlBuilder
{
    use SetUrls;

    private $aspsp;
    private $clientId;
    private $codeChallenge;
    private $state;
    private $redirectUri;
    /**
     * @var string
     */
    private $method;

    /**
     * RedsysTokenUrlBuilder constructor.
     * @param string $aspsp
     * @param string $clientId
     * @param string $codeChallenge
     * @param string $state
     * @param string $redirectUri
     * @param string $method
     */
    public function __construct(
        string $aspsp,
        string $clientId,
        string $codeChallenge,
        string $state,
        string $redirectUri,
        string $method = 'plain'
    )
    {
        $this->aspsp = $aspsp;
        $this->clientId = $clientId;
        $this->codeChallenge = $codeChallenge;
        $this->state = $state;
        $this->redirectUri = $redirectUri;
        $this->method = $method;
    }

    /**
     * {@inheritDoc}
     */
    public function __invoke(): string
    {
        if (is_null($this->urls)) {
            throw new Psd2UrlNotSetException;
        }
        $data = [
            'response_type' => 'code',
            'client_id' => $this->clientId,
            'scope' => 'PIS SVA',
            'state' => $this->state,
            'redirect_uri' => $this->redirectUri,
            'code_challenge' => $this->codeChallenge,
            'code_challenge_method' => $this->method,
        ];
        $endpoint = http_build_query($data, '', '&', PHP_QUERY_RFC3986);
        return $this->urls->tokenRequestUrl() . $this->aspsp . '/authorize?' . $endpoint;
    }
}
