<?php


namespace codehell\Psd2\Domain\DomainException;


class Psd2InitPaymentRejectedException extends Psd2Exception
{
    public $code = "E300";
    public $message = "the payment has been rejected";
}
