<?php


namespace Psd2\Domain;


interface PaymentRequests
{
    public function initPayment($payload, $requestId, $psuIp, $digest, $signature, $redirectUrl): string;
    public function checkPayment($requestId, $psuIp, $digest, $signature, $stateUrl): string;
}
