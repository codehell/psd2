<?php
declare(strict_types=1);

namespace Psd2;

class Auth
{
    public function getCertificate()
    {
        return file_get_contents(__DIR__ . '/../credentials/certificate.txt');
    }

    public function getDigest($payload)
    {
        $encoded = base64_encode(hash('sha256', $payload, true));
        return $encoded;
    }

    public function getSHA256Digest($payload)
    {
        return 'SHA-256=' . $this->getDigest($payload);
    }

    public function getSignature($digest, $uuid, $redirectUrl = null)
    {
        if (is_null($redirectUrl)) {
            $textToSign = "digest: SHA-256={$digest}\nx-request-id: {$uuid}";
        } else {
            $textToSign = "digest: SHA-256={$digest}\nx-request-id: {$uuid}\ntpp-redirect-uri: {$redirectUrl}";
        }
        $privateKey = openssl_pkey_get_private(file_get_contents(__DIR__ . '/../credentials/tpp_generico.pem'));
        openssl_sign($textToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        return base64_encode($signature);
    }

    public function buildSignature($signature, $file)
    {
        $cert = openssl_x509_read(file_get_contents($file));
        $certs = openssl_x509_parse($cert);
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
        $signature = "keyId=\"{$keyId}\",algorithm=\"{$algorithm}\",headers=\"{$headers}\",signature=\"{$signature}\"";
        return $signature;
    }

    public function tokenUrl($aspsp, $clientId, $codeChallenge, $state, $redirectUri, $method = 'plain')
    {
        $data = [
            'response_type' => 'code',
            'client_id' => $clientId,
            'scope' => 'PIS AIS SVA',
            'state' => $state,
            'redirect_uri' => $redirectUri,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => $method,
        ];
        $endpoint = http_build_query($data, null, '&', PHP_QUERY_RFC3986);
        // TODO Put address in config file.
        $url = 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/' . $aspsp . '/authorize?' . $endpoint;
        return $url;
    }
}
