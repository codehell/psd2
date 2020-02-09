<?php
declare(strict_types=1);

namespace Psd2\tests;

use Exception;
use Ramsey\Uuid\Uuid;
use Psd2\RedsysAspsps;
use Psd2\RedsysSignature;
use PHPUnit\Framework\TestCase;

final class RedsysAspspsTest extends TestCase
{
    /**
     * @test
     */
    public function it_get_aspsps_list()
    {
        $expected = <<<EOD
{"aspsps":[{"bic":"","name":"REDSYS SERVICIOS DE PROCESAMIENTO S.L.","apiName":"redsys"},{"bic":"BACAESMMXXX","name":"ANDBANK ESPAÑA S.A.","apiName":"andbank"},{"bic":"UCJAES2MXXX","name":"UNICAJA BANCO, S.A.","apiName":"unicajabanco"},{"bic":"CAHMESMMXXX","name":"BANKIA, S.A.","apiName":"bankia"},{"bic":"BMARES2MXXX","name":"BANCA MARCH, S.A.","apiName":"bancamarch"},{"bic":"ALCLESMMXXX","name":"BANCO ALCALA S.A.","apiName":"bancoalcala"},{"bic":"SELFESMMXXX","name":"SELF TRADE BANK, S.A.U.","apiName":"selfbank"},{"bic":"BSCHESMMXXX","name":"BANCO SANTANDER, S.A.","apiName":"bancosantander"},{"bic":"CASDESBBXXX","name":"CAJA DE ARQUITECTOS, S.C.C.","apiName":"ARQUIA"},{"bic":"EVOBESMMXXX","name":"EVO BANCO S.A.U.","apiName":"EVOBANCO"},{"bic":"CLPEES2MXXX","name":"CAJA LABORAL POPULAR, C.C.","apiName":"laboralkutxa"},{"bic":"OPENESMMXXX","name":"OPENBANK","apiName":"openbank"},{"bic":"CECAESMM056","name":"COLONYA - CAIXA D'ESTALVIS DE POLLENSA.","apiName":"COLONYA"},{"bic":"BKOAES22XXX","name":"BANKOA, S.A.","apiName":"BANKOA"},{"bic":"PICHESMMXXX","name":"BANCO PICHINCHA C.A.","apiName":"PICHINCHA"},{"bic":"POPLESMMXXX","name":"WIZINK BANK, S.A.","apiName":"wizink"},{"bic":"BEDFESM1XXX","name":"BANCO EUROPEO DE FINANZAS S.A.","apiName":"BANCOEUROPEODEFINANZAS"},{"bic":"BBVAESMMXXX","name":"BANCO BILBAO VIZCAYA ARGENTARIA, S.A.","apiName":"BBVA"},{"bic":"BCOEESMM081","name":"EUROCAJA RURAL, S.C.C.","apiName":"EUROCAJARURAL"},{"bic":"BKBKESMMXXX","name":"BANKINTER, S.A.","apiName":"bankinter"},{"bic":"BASKES2BXXX","name":"KUTXABANK, S.A.","apiName":"kutxabank"},{"bic":"CAZRES2ZXXX","name":"IBERCAJA BANCO, S.A.","apiName":"ibercaja"},{"bic":"TESTINTAXXX","name":"testint","apiName":"testint"},{"bic":"BCOEESMMXXX","name":"BANCO COOPERATIVO ESPAÑOL, S.A.","apiName":"BCE"},{"bic":"BFIVESBBXXX","name":"BANCO MEDIOLANUM, S.A.","apiName":"BancoMediolanum"},{"bic":"CAIXESBBXXX","name":"CAIXABANK, S.A.","apiName":"caixabank"},{"bic":"INVLESMMXXX","name":"BANCO INVERSIS, S.A.","apiName":"inversis"},{"bic":"BCCAESMMXXX","name":"BCC GRUPO CAJAMAR","apiName":"grupocajamar"},{"bic":"ETICES21XXX","name":"FIARE BANCA ETICA","apiName":"FIARE"},{"bic":"RENBESMMXXX","name":"RENTA 4 BANCO, S.A.","apiName":"renta4"},{"bic":"BSABESBBXXX","name":"BANCO DE SABADELL, S.A","apiName":"BancSabadell"},{"bic":"CSURES2CXXX","name":"CAJASUR BANCO, S.A.","apiName":"cajasur"}]}\n
EOD;

        $requestId = '';
        try {
            $requestId = Uuid::uuid4()->toString();
        } catch (Exception $e) {
        }
        $signature = new RedsysSignature;
        $digest = $signature->getSHA256Digest('');
        $pk = file_get_contents(__DIR__ . '/credentials/pri_key.pem');
        $cert = file_get_contents(__DIR__ . '/credentials/certificate.pem');
        $textCert = file_get_contents(__DIR__ . '/credentials/certificate.txt');
        $generatedSignature = $signature->getSignature($digest, $requestId, $pk);
        $headerSignature = $signature->headerSignature($generatedSignature, $cert);
        $response = new RedsysAspsps($requestId, $digest, $headerSignature, $textCert);
        $this->assertEquals($expected, $response());
    }
}
