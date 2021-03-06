<?php
declare(strict_types=1);

namespace Codehell\Psd2\Tests\Infrastructure\Redsys;

use PHPUnit\Framework\TestCase;
use Codehell\Psd2\Infrastructure\Redsys\RedsysSandboxUrlsContainer;
use Codehell\Psd2\Infrastructure\Redsys\RedsysTokenUrlBuilder;
use Codehell\Psd2\Domain\DomainException\Psd2UrlNotSetException;

class RedsysTokenUrlBuilderTest extends TestCase
{
    /**
     * @test
     * @throws Psd2UrlNotSetException
     */
    public function it_return_token_url()
    {
        $expected = 'https://apis-i.redsys.es:20443/psd2/xs2a/api-oauth-xs2a/services/rest/bancosantander/authorize?response_type=code&client_id=00000-000-00000&scope=PIS%20SVA&state=1234&redirect_uri=%2F&code_challenge=123asqwerpoiulakj230947lsaksnfa&code_challenge_method=SHA256';
        $config = json_decode(file_get_contents(__DIR__ . '/../../credentials/config.json'), true);
        $pkce = '123asqwerpoiulakj230947lsaksnfa';
        $urls = new RedsysSandboxUrlsContainer();
        $tokenUrl = new RedsysTokenUrlBuilder(
            'bancosantander',
            $config['clientId'],
            $pkce,
            '1234',
            '/',
            'plain'
        );
        $tokenUrl->setUrls($urls);
        $result = $tokenUrl();
        self::assertEquals($expected, $result);
    }

    /**
     * @test
     * @throws Psd2UrlNotSetException
     */
    public function it_must_return_route_not_configured_exception()
    {
        $pkce = '123asqwerpoiulakj230947lsaksnfa';
        $tokenUrl = new RedsysTokenUrlBuilder(
            'bancosantander',
            '00000-000-00000',
            $pkce,
            '1234',
            '/',
            'SHA256'
        );
        $this->expectException(Psd2UrlNotSetException::class);
        $tokenUrl();
    }
}
