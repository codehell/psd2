<?php


namespace Psd2\Domain;


interface Urls
{
    public function tokenRequestUrl(): string;
    public function baseUrl(): string;
}
