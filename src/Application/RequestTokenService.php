<?php
declare(strict_types=1);

namespace Codehell\Psd2\Application;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Domain\TokenRequester;

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

    /**
     * @param string $code
     * @param string $aspsp
     * @param string $clientId
     * @param string $redirectUrl
     * @param string $codeVerifier
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function __invoke(string $code, string $aspsp, string $clientId, string $redirectUrl, string $codeVerifier): string
    {
        return $this->request->getToken($code, $aspsp, $clientId, $redirectUrl, $codeVerifier);
    }
}
