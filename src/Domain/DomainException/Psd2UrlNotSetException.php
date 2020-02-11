<?php
declare(strict_types=1);

namespace Psd2\Domain\DomainException;


class Psd2UrlNotSetException extends Psd2Exception
{
    public $code = "urlNotConfigured";
    public $message = "the routes has not been configured";
}
