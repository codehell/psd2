<?php
declare(strict_types=1);

namespace Codehell\Psd2\Tests\Application;


use PHPUnit\Framework\TestCase;
use Codehell\Psd2\Infrastructure\Redsys\RedsysSandboxUrlsContainer;
use Codehell\Psd2\Infrastructure\Redsys\Requests\Payment;
use Codehell\Psd2\Application\RequestPaymentService;
use Codehell\Psd2\Infrastructure\Redsys\RedsysPaymentRequester;
use Ramsey\Uuid\Uuid;

class RequestPaymentServiceTest extends TestCase
{
    /**
     * @test
     * @throws \Exception
     */
    public function it_receives_an_error_response()
    {
        $config = json_decode(file_get_contents(__DIR__ . '/../credentials/config.json'), true);
        $certificate = file_get_contents(__DIR__ . '/../credentials/certificate.pem');
        $priKey = file_get_contents(__DIR__ . '/../credentials/pri_key.pem');
        $payment = new Payment(
            '',
            'bancosantander',
            '',
            Uuid::uuid4()->toString(),
            'xLd1pyB9DK9XZEZ0yuZNBZ1wTpEVjcmPGRveh5XXxMuHP5v6k0N5eH3j1YWvZhiv',
            '192.168.1.33',
            '/',
            $certificate,
            '100',
            'ES2222222222222222222222',
            'ivan',
            $priKey,
            $config['clientId'],
            'v1'
        );
        $redsysRequester = new RedsysPaymentRequester($payment);
        $urls = new RedsysSandboxUrlsContainer();
        $redsysRequester->setUrls($urls);
        $service = new RequestPaymentService($redsysRequester);
        var_dump($service());
    }
}
