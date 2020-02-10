<?php


namespace Psd2\Application\Redsys;

use Psd2\Domain\TokenRequest;

final class TokenRequester
{
    /**
     * @var TokenRequest
     */
    private $request;

    /**
     * TokenRequester constructor.
     * @param TokenRequest $request
     */
    public function __construct(TokenRequest $request)
    {
        $this->request = $request;
    }

    public function __invoke(string $code, string $aspsp, string $clientId, string $redirectUrl, string $codeVerifier): string
    {
        return $this->request->getToken($code, $aspsp, $clientId, $redirectUrl, $codeVerifier);
    }
}
