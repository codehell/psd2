<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain;


interface StringInvoker
{
    public function __invoke(): string;
}
