<?php
declare(strict_types=1);

namespace Psd2\Application;


use Psd2\Domain\TokenRequester;

final class RequestTokenService
{
    /**
     * @var TokenRequester
     */
    private $request;

    /**
     * TokenRequester constructor.
     * @param TokenRequester $request
     */
    public function __construct(TokenRequester $request)
    {
        $this->request = $request;
    }

    public function __invoke(string $code, string $aspsp, string $clientId, string $redirectUrl, string $codeVerifier): string
    {
        return $this->request->getToken($code, $aspsp, $clientId, $redirectUrl, $codeVerifier);
    }
}
