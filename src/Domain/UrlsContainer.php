<?php


namespace Psd2\Domain;


interface UrlsContainer
{
    public function tokenRequest(): string;
    public function baseUrl(): string;
}
