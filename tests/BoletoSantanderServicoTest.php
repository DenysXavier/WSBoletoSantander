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

use TIExpert\WSBoletoSantander\BoletoSantanderServico;

class BoletoSantanderServicoTest extends PHPUnit_Framework_TestCase {

    private static $boleto;
    private static $faker;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$faker = Faker\Factory::create("pt_BR");

        $convenio = new TIExpert\WSBoletoSantander\Convenio();
        $pagador = new \TIExpert\WSBoletoSantander\Pagador(1, mt_rand(100000, 999999), self::$faker->name, self::$faker->streetAddress, self::$faker->city, self::$faker->city, self::$faker->stateAbbr, self::$faker->postcode);
        $titulo = new TIExpert\WSBoletoSantander\Titulo(mt_rand(0, 9999999), mt_rand(100000000, 999999999), mt_rand(100000000, 999999999), "now", NULL, NULL, NULL, new \TIExpert\WSBoletoSantander\InstrucoesDeTitulo());

        self::$boleto = new \TIExpert\WSBoletoSantander\Boleto($convenio, $pagador, $titulo);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeParaSolicitarTicketDeInclusao() {
        $ticketBase64 = self::$faker->regexify("[A-Za-z0-9+/]{48}");

        $comunicador = $this->getMockBuilder("TIExpert\WSBoletoSantander\ComunicadorCurlSOAP")->getMock();
        $comunicador->method("chamar")->willReturn('<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <dlwmin:createResponse xmlns:dlwmin="http://impl.webservice.dl.app.bsbr.altec.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <TicketResponse>
        <retCode>0</retCode>
        <ticket>' . $ticketBase64 . '</ticket>
      </TicketResponse>
    </dlwmin:createResponse>
  </soapenv:Body>
</soapenv:Envelope>');

        $svc = new BoletoSantanderServico($comunicador);
        $ticket = $svc->solicitarTicketInclusao(self::$boleto);

        $this->assertEquals($ticketBase64, $ticket->getAutenticacao());
    }

}
