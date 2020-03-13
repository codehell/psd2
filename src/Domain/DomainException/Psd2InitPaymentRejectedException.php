<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain\DomainException;


class Psd2InitPaymentRejectedException extends Psd2Exception
{
    public $code = "E300";
    public $status = "E300";
    public $httpStatus = "500";
    public $message = "the payment has been rejected";
}
