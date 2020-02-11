<?php
declare(strict_types=1);

namespace Psd2\Tests\Application;


use PHPUnit\Framework\TestCase;
use Psd2\Infrastructure\Redsys\RedsysSandboxUrlsContainer;
use Psd2\Infrastructure\Redsys\Requests\Payment;
use Psd2\Application\RequestPaymentService;
use Psd2\Infrastructure\Redsys\RedsysPaymentRequester;
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
            'bancosabadell',
            '',
            Uuid::uuid4()->toString(),
            'B5fpyDG3AKlPLNrionRJRXpMr1FkeACCo1Bm9lkHW7Iw4vegshKYyW2eL6mtwKE1',
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
