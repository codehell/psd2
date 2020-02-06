<?php
declare(strict_types=1);

namespace Psd2;


final class TokenUrl
{
    public function __invoke($aspsp, $clientId, $codeChallenge, $state, $redirectUri, $method = 'plain'): string
    {
        $data = [
            'response_type' => 'code',
            'client_id' => $clientId,
            'scope' => 'PIS SVA',
            'state' => $state,
            'redirect_uri' => $redirectUri,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => $method,
        ];
        $endpoint = http_build_query($data, null, '&', PHP_QUERY_RFC3986);
        // TODO Put address in config file.
        return 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/' .
            $aspsp . '/authorize?' . $endpoint;
    }
}