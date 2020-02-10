<?php
declare(strict_types=1);

namespace Psd2\Infrastructure\Redsys;


use Psd2\Domain\TokenUrlBuilder;
use Psd2\Domain\Urls;

final class RedsysTokenUrlBuilder implements TokenUrlBuilder
{
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
     * @var Urls
     */
    private $urls;

    public function __construct(
        Urls $urls,
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
        $this->urls = $urls;
    }

    public function __invoke(): string
    {
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
        return $this->urls->tokenRequestUrl() .
            $this->aspsp . '/authorize?' . $endpoint;
    }
}
