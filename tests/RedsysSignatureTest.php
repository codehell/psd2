<?php
declare(strict_types=1);
namespace Psd2\tests;

use PHPUnit\Framework\TestCase;
use Psd2\RedsysSignature;

final class RedsysSignatureTest extends TestCase
{
    private $testData;
    private $cert;
    private $privateKey;
    private $expectedSignature;

    public function __construct()
    {
        parent::__construct();
        $this->testData = <<<EOD
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
        $this->cert = <<<EOD
-----BEGIN CERTIFICATE-----
MIID+TCCAuGgAwIBAgIBATANBgkqhkiG9w0BAQsFADBsMRUwEwYDVQQDDAxUUFAg
R2VuZXJpY28xETAPBgNVBAsMCFRQUCBVbml0MREwDwYDVQQKDAhUUFAgTmFtZTEP
MA0GA1UEBwwGTWFkcmlkMQ8wDQYDVQQIDAZNYWRyaWQxCzAJBgNVBAYTAkVTMB4X
DTE5MDMyNjEwNTAxNFoXDTIwMDMyNTEwNTAxNFowgYUxFTATBgNVBAMMDFRQUCBH
ZW5lcmljbzERMA8GA1UECwwIVFBQIFVuaXQxETAPBgNVBAoMCFRQUCBOYW1lMQ8w
DQYDVQQHDAZNYWRyaWQxDzANBgNVBAgMBk1hZHJpZDELMAkGA1UEBhMCRVMxFzAV
BgNVBGEMDlBTREVTLVJEUy04MDAwMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIB
CgKCAQEAgcYql6G7OX4WJAEZ/TyRfZnYGyrC30gkw3TuW7wij3SqbAsWzV+KysiW
z1gfSEH54ArWg5tpag8msIfeRDdydfepYsTQ2EFKGSzVlzCm4U6Y6l+0SA2AwpEB
PE4t2XbJFYSDlo6XaSHYl6pZS1Tv5O3rOr6Y9AfF6L3fAMhyKbqgVlMpreaP2FbY
9H0BOb94+fDeEik6epJeGYeAWBATbpY60S1WhbjhJ4Dmi0nBYlDGTjKe8qTLIbJl
MZA2DwS0OSc87DFUDc+wDQfTMmta2b4nS1KQnoXC7EK4c7CRx2GI290GTO/52OnV
bfq5CBXJSLH+/sgttBXvRdvah2hj6QIDAQABo4GLMIGIMHsGCCsGAQUFBwEDBG8w
bQYGBACBmCcCMGMwEQYHBACBmCcBAQwGUFNQX0FTMBEGBwQAgZgnAQIMBlBTUF9Q
STARBgcEAIGYJwEDDAZQU1BfQUkwEQYHBACBmCcBBAwGUFNQX0lDDA1CYW5rIG9m
IFNwYWluDAZFUy1CREUwCQYDVR0TBAIwADANBgkqhkiG9w0BAQsFAAOCAQEAOS25
emeaMO2vT0u04v4ridx7I1IPFbSyOiE5YC8K25VBRg09c7HtQB/SnNSukx35UuRl
V5u83jgSk28Qr/JSn8kEcIRY6wnD4ZJvaozDMnY258VhKxrcuFAbmDQhVtkehBH/
nddHgDyBuLURPsAy9zb7TOxaDrmfsgLRKYTVnFBkhnda/lN4h3PgZFVIm0/E1PWh
pYKma7XNbjVxgcKbGuKM7Q9dfedaEUzwUMIPxFc1H96p8xCCTJNp/FAd51wp5N7n
0uRe1kx5YbptdrmB+xzCg495+t24K3SfFhaeujiJflUtkEF+9UoYo+w1rfWfErHm
UPnqhMb0AhFJ66Uetg==
-----END CERTIFICATE-----
EOD;

        $this->privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEogIBAAKCAQEAgcYql6G7OX4WJAEZ/TyRfZnYGyrC30gkw3TuW7wij3SqbAsW
zV+KysiWz1gfSEH54ArWg5tpag8msIfeRDdydfepYsTQ2EFKGSzVlzCm4U6Y6l+0
SA2AwpEBPE4t2XbJFYSDlo6XaSHYl6pZS1Tv5O3rOr6Y9AfF6L3fAMhyKbqgVlMp
reaP2FbY9H0BOb94+fDeEik6epJeGYeAWBATbpY60S1WhbjhJ4Dmi0nBYlDGTjKe
8qTLIbJlMZA2DwS0OSc87DFUDc+wDQfTMmta2b4nS1KQnoXC7EK4c7CRx2GI290G
TO/52OnVbfq5CBXJSLH+/sgttBXvRdvah2hj6QIDAQABAoIBAAvcduwhBiG9Gnzz
9NImhQ27z/q7r9AEogeTWm3cBjSibyks4kqnHuJl7xLIdSN/lD0cAHtK6xmchObB
pFkL0FRj7IFwh74cSY9/f44wDjCtpGBXmvyy7z/ZTQmcA6jKqJpk4DoUklYzl75U
/ZBbFQoRjNxzqzsysq4RHJ7Ps0+I8w1F3TJbS5DPZRhnjDBmlMO/qQER3nuuD10C
kpMNyHpf4wWykEizqrA1xwTR5WNN8PLG2rwfZFBgAtnAGkmIUX6SdJ0CZPkmp6up
oiB57xiJSeJr+mFPOUfzf1fzzMDCO8uN2F/pxZ5j67Lo+rMocPqJN08+EKKX2s5w
wc2mvpkCgYEA5JujOsy2Vlta+b//gJZ0DD92PEKgaSI55jeQ+ivOYHODyQWVG9U2
pmzjmGOAszCiETlu7NVbDF/iojM/PZLsYMAn4i2Ut2HjFp8v/j9/THDuUQ0scOIb
X3fBidyzXhefkgPAMCFJtPm4vwN0lvreD+70spg4nIe8frNUUXK5zqsCgYEAkVLh
Z048Bgb8eWl1L5vpKclrTLsnZPsnXFBQoiOxm2H4JMyjV4a2kRCVTbPXUi52ifh6
+gsTiOrTeGIcfKVanIQyaxfkEgyc/HtgxeaGh4Iuhq1b2p3VCXv+Q2JJgGuBfeev
QnqJkbUoR51aYtYfW4q1cPb0xpFGfWGSCeAXR7sCgYBOUxzL6pOWZtp2s1ehAOtl
vuCNqO441ycrP2KGtDeTSECgwS7jSFvfDXO1JHJV8pW0fQ8KSddWGAwYMqK6P/qI
8402qxNG+VzKxWO2Ip9E23RrhK3zRyTFwswmpd02obVkW7CfTOhp/x1TJKXvjKhE
oURpZEoRJ1hnuJ1tAney3QKBgBylD5UDUMWHXwC3jsfBnaYE88d+JHe9IbcKhbpP
+tQ77WOjipbYEOhrMmLMjia+zdLITcU3pbQZRXG3NRJEraWoUR/W57e/ELqbvymD
FQVk3bLiPPbMoY9rB2VU3FQZ3L5qips5+B/ma3giRgyxVCEBKF6J7xmszQ4ty1Fz
AGO9AoGAKC7PQlnCSre2SDfzYSCqYVrz4APKE4ko2WCqi/2ZEVEiMgJgsggrLgBk
BmircpH2Ma+lcGG2+poPaBP93hgA4J4wpOreJDTxiyxEF/Sxz/FygS9KK9fTKH0I
VHs8mbGqNbul79ANcyD9VIlhyKBCEWnG7o2yIhXLUdQHREUQhRQ=
-----END RSA PRIVATE KEY-----
EOD;
        $this->expectedSignature = 'keyId="SN=1,CA=C=ES,ST=Madrid,L=Madrid,O=TPP Name,OU=TPP Unit,CN=TPP Generico",' .
            'algorithm="SHA-256",headers="digest x-request-id",' .
            'signature="C/XNRPz/7XJHXR+jNRRaZcrFM1vE32tXxrmeWRyZGfrVN3veRDrK4tOr4+hSKVI9OlhgcqBrVY7IWSmbP9+SqH2qxIJffzr+' .
            'mhQ71N8njTrJb5XYJtUpnHjeHXnPvr6pXa3vyWGm6kFli6R1wn4tEgl2Qxuj2HApFcDnU204rSzEHt2iwaCCtVVG6eK4AupzYMo6Y/yNPRj' .
            '2f64iD62k9nIfmICxY3CRv6Unkedg100m4sRoCy4DcRk47cfid/307FtF8NmwYuTPe/MAvxT7wpI9p5AVRcQ+OPSoAqr/ukkQgc+37ClyxS' .
            'oHf9wWZ/h39X3lM9/5lKmtS7tpBj5wPg=="';
    }

    /**
     * @test
     */
    public function get_digest(): void
    {
        $actual = (new RedsysSignature)->getDigest($this->testData);
        $expected = 'pfHPQFso5E7SlQfg9kSVhZuod4k9KnFFEtFs472L5WI=';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function get_SHA256_digest(): void
    {
        $actual = (new RedsysSignature)->getSHA256Digest($this->testData);
        $expected = 'SHA-256=pfHPQFso5E7SlQfg9kSVhZuod4k9KnFFEtFs472L5WI=';
        $this->assertEquals($expected, $actual);
    }

    /**
     * @test
     */
    public function test_build_signature(): void
    {
        $signature = new RedsysSignature();
        $digest = $signature->getSHA256Digest($this->testData);
        $result = $signature->getSignature(
            $digest,
            'bf5113e6-3c04-4b30-b08b-505e83200c82',
            $this->privateKey
        );
        $actual = $signature->headerSignature($result, $this->cert);
        $this->assertEquals($this->expectedSignature, $actual);
    }
}
