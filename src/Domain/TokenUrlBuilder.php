<?php
declare(strict_types=1);

namespace Psd2\Domain;


use Psd2\Domain\DomainException\Psd2UrlNotSetException;

interface TokenUrlBuilder extends UrlsSetter
{
    /**
     * @return string
     * @throws Psd2UrlNotSetException
     */
    public function __invoke(): string;
}
