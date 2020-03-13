<?php
declare(strict_types=1);

namespace Codehell\Psd2\Domain\DomainException;


use Exception;

abstract class Psd2Exception extends Exception
{
    protected $status;
    protected $httpStatus;

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getHttpStatus()
    {
        return $this->httpStatus;
    }
}
