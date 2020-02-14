<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain\DomainTraits;


use Codehell\Psd2\Domain\UrlsContainer;

trait SetUrls
{
    /**
     * @var UrlsContainer
     */
    protected $urls;

    /**
     * @param UrlsContainer $urls
     */
    public function setUrls(UrlsContainer $urls)
    {
        $this->urls = $urls;
    }
}
