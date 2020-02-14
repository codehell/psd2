<?php
declare(strict_types=1);


namespace Codehell\Psd2\Domain;

interface UrlsContainer
{
    public function tokenRequestUrl(): string;
    public function baseUrl(): string;
}
