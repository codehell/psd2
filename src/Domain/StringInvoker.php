<?php
declare(strict_types=1);

namespace Psd2\Domain;


interface StringInvoker
{
    public function __invoke(): string;
}
