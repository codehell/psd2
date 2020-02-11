<?php
declare(strict_types=1);

namespace Psd2\Domain;


final class Signer
{
    public function getSignature($digest, $uuid, $privateKey, $redirectUrl = null): string
    {
        if (is_null($redirectUrl)) {
            $textToSign = "digest: {$digest}\nx-request-id: {$uuid}";
        } else {
            $textToSign = "digest: {$digest}\nx-request-id: {$uuid}\ntpp-redirect-uri: {$redirectUrl}";
        }
        $parsedKey = openssl_pkey_get_private($privateKey);
        openssl_sign($textToSign, $signature, $parsedKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    public function headerSignature($signature, $cert): string
    {
        $crudeCert = openssl_x509_read($cert);
        $certs = openssl_x509_parse($crudeCert);
        $subject = $certs['subject'];
        $issuer = $certs['issuer'];
        $sn = $certs['serialNumber'];
        $ca = $subject['C'];
        $st = $issuer['ST'];
        $l = $issuer['L'];
        $o = $issuer['O'];
        $ou = $issuer['OU'];
        $cn = $issuer['CN'];
        $keyId = "SN={$sn},CA=C={$ca},ST={$st},L={$l},O={$o},OU={$ou},CN={$cn}";
        $algorithm = "SHA-256";
        $headers = "digest x-request-id";
        return "keyId=\"{$keyId}\",algorithm=\"{$algorithm}\",headers=\"{$headers}\",signature=\"{$signature}\"";
    }

    public function getDigest($payload): string
    {
        return base64_encode(hash('sha256', $payload, true));
    }

    public function getSHA256Digest($payload): string
    {
        return 'SHA-256=' . $this->getDigest($payload);
    }
}
