<?php
declare(strict_types=1);

namespace Psd2\tests;

use Psd2\RedsysTokenUrl;
use PHPUnit\Framework\TestCase;

class RedsysTokenUrlTest extends TestCase
{
    /**
     * @test
     */
    public function it_return_token_url()
    {
        $expected = 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/bancosantander/authorize?response_type=code&client_id=00000-000-00000&scope=PIS%20SVA&state=1234&redirect_uri=%2F&code_challenge=123asqwerpoiulakj230947lsaksnfa&code_challenge_method=SHA256';
        $pkce = '123asqwerpoiulakj230947lsaksnfa';
        $tokenUrl = new RedsysTokenUrl(
            'bancosantander',
            '00000-000-00000',
            $pkce,
            '1234',
            '/',
            'SHA256'
        );
        $result = $tokenUrl();
        self::assertEquals($expected, $result);
    }
}
