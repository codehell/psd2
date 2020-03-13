<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain\DomainException;


class Psd2UrlNotSetException extends Psd2Exception
{
    public $code = "urlNotConfigured";
    public $status = "E300";
    public $httpStatus = "500";
    public $message = "the routes has not been configured";
}
