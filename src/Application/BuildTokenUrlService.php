<?php
declare(strict_types=1);

namespace Codehell\Psd2\Application\Redsys;


use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;
use Codehell\Psd2\Domain\TokenUrlBuilder as TokenUrlGenerator;

final class BuildTokenUrlService
{
    /**
     * @var BuildTokenUrlService
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

    /**
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function __invoke(): string
    {
       return $this->urlBuilder->__invoke();
    }
}
