<?php

/*
 * Copyright 2017 Denys.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

use TIExpert\WSBoletoSantander\ComunicadorCurlSOAP;

class ComunicadorCurlSOAPTest extends PHPUnit_Framework_TestCase {

    private static $comunicadorCurlSoap;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$comunicadorCurlSoap = new ComunicadorCurlSOAP();
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function metodoGetCurlConfiguracaoArrayDeveVirComAsConfiguracoesMinimasParaFazerUmChamado() {
        $itensNecessarios = array(CURLOPT_TIMEOUT, CURLOPT_RETURNTRANSFER, CURLOPT_POST, CURLOPT_POSTFIELDS, CURLOPT_HTTPHEADER, CURLOPT_SSL_VERIFYPEER, CURLOPT_SSL_VERIFYHOST);

        $configResultante = self::$comunicadorCurlSoap->prepararConfiguracaoEndpoint();

        foreach ($itensNecessarios as $chave) {
            $this->assertArrayHasKey($chave, $configResultante);
        }
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \InvalidArgumentException
     */
    public function umaExceptionDeveSerLancadaAoTentarConverterSOAPFaultStringParaExceptionUmSOAPFaultQueNaoEhString() {
        $SOAPFaultXML = '<SOAP-1_2-ENV:Fault xmlns:sqlsoapfaultcode="http://schemas.microsoft.com/sqlserver/2004/SOAP/SqlSoapFaultCode">
  <SOAP-1_2-ENV:Code>
    <SOAP-1_2-ENV:Value>SOAP-1_2-ENV:Sender</SOAP-1_2-ENV:Value>
  </SOAP-1_2-ENV:Code>
  <SOAP-1_2-ENV:Reason>
    <SOAP-1_2-ENV:Text xml:lang="en-US">There was an error in the incoming SOAP request packet:  Sender, InvalidXml</SOAP-1_2-ENV:Text>
  </SOAP-1_2-ENV:Reason>
</SOAP-1_2-ENV:Fault>';

        self::$comunicadorCurlSoap->converterSOAPFaultStringParaException($SOAPFaultXML);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test     
     */
    public function umaSOAPFaultStringPodeSerConvertidaParaException() {
        $faultMessage = 'java.lang.StringIndexOutOfBoundsException';
        $SoapFaultString = 'faultcode=&faultstring=' . $faultMessage . '&detail=';

        $excecao = self::$comunicadorCurlSoap->converterSOAPFaultStringParaException($SoapFaultString);

        $this->assertEquals($faultMessage, $excecao->getMessage());
    }
}
