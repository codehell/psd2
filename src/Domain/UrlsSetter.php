<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain;


interface UrlsSetter
{
    /**
     * @param UrlsContainer $urls
     * @return mixed
     */
    public function setUrls(UrlsContainer $urls);
}
