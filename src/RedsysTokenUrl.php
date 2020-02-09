<?php
declare(strict_types=1);

namespace Psd2;


final class RedsysTokenUrl
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

    public function __construct($aspsp, $clientId, $codeChallenge, $state, $redirectUri, $method = 'plain')
    {
        $this->aspsp = $aspsp;
        $this->clientId = $clientId;
        $this->codeChallenge = $codeChallenge;
        $this->state = $state;
        $this->redirectUri = $redirectUri;
        $this->method = $method;
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
        $endpoint = http_build_query($data, null, '&', PHP_QUERY_RFC3986);
        return 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/' .
            $this->aspsp . '/authorize?' . $endpoint;
    }
}
