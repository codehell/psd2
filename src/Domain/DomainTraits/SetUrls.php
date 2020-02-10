<?php
declare(strict_types=1);

namespace Psd2\Domain\DomainTraits;


use Psd2\Domain\Urls;

trait SetUrls
{
    /**
     * @var Urls
     */
    protected $urls;

    /**
     * @param Urls $urls
     */
    public function setUrls(Urls $urls)
    {
        $this->urls = $urls;
    }
}
