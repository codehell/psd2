<?php
declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Psd2\Auth;

final class AuthTest extends TestCase
{
    private $testData;
    private $auth;

    public function __construct()
    {
        parent::__construct();
        $this->auth = new Auth();
        $this->testData =  <<<EOD
{
  "instructedAmount" : {
    "currency" : "EUR",
    "amount" : "16.00"
  },
  "debtorAccount" : {
    "iban" : "ES5140000001050000000001",
    "currency" : "EUR"
  },
  "creditorName" : "Cred. Name",
  "creditorAccount" : {
    "iban" : "ES6621000418401234567891",
    "currency" : "EUR"
  },
  "creditorAddress" : {
    "street" : "Ejemplo de calle",
    "buildingNumber" : "15",
    "city" : "Cordoba",
    "postalCode" : "14100",
    "country" : "ES"
  },
  "remittanceInformationUnstructured" : "Pago",
  "chargeBearer" : "CRED"
}
EOD;
    }
    public function testGetDigest(): void
    {
        $actual = $this->auth->getDigest($this->testData);
        $expected = 'pfHPQFso5E7SlQfg9kSVhZuod4k9KnFFEtFs472L5WI=';
        $this->assertEquals($expected, $actual);
    }

    public function testGetSHA256Digest()
    {
        $actual = $this->auth->getSHA256Digest($this->testData);
        $expected = 'SHA-256=pfHPQFso5E7SlQfg9kSVhZuod4k9KnFFEtFs472L5WI=';
        $this->assertEquals($expected, $actual);
    }

    public function testBuildSignature()
    {
        $digest = $this->auth->getDigest($this->testData);
        $signature = $this->auth->getSignature($digest, 'bf5113e6-3c04-4b30-b08b-505e83200c82', '/');
        $actual = $this->auth->buildSignature($signature, __DIR__ . '../../credentials/certificate.pem');
        $this->assertEquals('', $actual);
    }
}