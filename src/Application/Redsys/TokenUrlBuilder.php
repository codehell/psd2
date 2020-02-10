<?php


namespace Psd2\Application\Redsys;


use Psd2\Domain\TokenUrlBuilder as TokenUrlGenerator;

final class TokenUrlBuilder
{
    /**
     * @var TokenUrlBuilder
     */
    private $urlBuilder;

    /**
     * TokenRequest constructor.
     * @param TokenUrlGenerator $urlBuilder
     */
    public function __construct(TokenUrlGenerator $urlBuilder)
    {
        $this->urlBuilder = $urlBuilder;
    }

    public function __invoke(): string
    {
       return $this->urlBuilder->__invoke();
    }
}
