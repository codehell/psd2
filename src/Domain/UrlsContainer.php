<?php
declare(strict_types=1);


namespace Psd2\Domain;

interface UrlsContainer
{
    public function tokenRequestUrl(): string;
    public function baseUrl(): string;
}
