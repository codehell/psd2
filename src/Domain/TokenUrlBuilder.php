<?php


namespace Psd2\Domain;



use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface TokenUrlBuilder extends SetUrls
{
    /**
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function __invoke(): string;
}
