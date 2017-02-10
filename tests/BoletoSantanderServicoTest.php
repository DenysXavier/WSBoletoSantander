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

    private static $ticket;
    private static $boleto;
    private static $faker;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$faker = Faker\Factory::create("pt_BR");

        $convenio = new TIExpert\WSBoletoSantander\Convenio();
        $pagador = new \TIExpert\WSBoletoSantander\Pagador(1, mt_rand(100000, 999999), self::$faker->name, self::$faker->streetAddress, self::$faker->city, self::$faker->city, self::$faker->stateAbbr, self::$faker->postcode);
        $titulo = new TIExpert\WSBoletoSantander\Titulo(mt_rand(0, 9999999), mt_rand(100000000, 999999999), mt_rand(100000000, 999999999), "now", NULL, NULL, NULL, new \TIExpert\WSBoletoSantander\InstrucoesDeTitulo());

        self::$boleto = new \TIExpert\WSBoletoSantander\Boleto($convenio, $pagador, $titulo);

        self::$ticket = new \TIExpert\WSBoletoSantander\Ticket();
        self::$ticket->setNsu(mt_rand(100000, 999999))->setAutenticacao(self::$faker->regexify("[A-Za-z0-9+/]{48}"));
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
    
    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeParaSolicitarTicketDeSondagem() {
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
        $ticket = $svc->solicitarTicketSondagem(self::$boleto->getConvenio());

        $this->assertEquals($ticketBase64, $ticket->getAutenticacao());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeParaIncluirUmTitulo() {
        $dataFake = date("dmY");

        $comunicador = $this->getMockBuilder("TIExpert\WSBoletoSantander\ComunicadorCurlSOAP")->getMock();
        $comunicador->method("chamar")->willReturn('<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <dlwmin:registraTituloResponse xmlns:dlwmin="http://impl.webservice.ymb.app.bsbr.altec.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <return xmlns:ns2="http://impl.webservice.ymb.app.bsbr.altec.com/">
        <codcede>' . self::$faker->regexify("[0-9]{9}") . '</codcede>
        <convenio>
          <codBanco>0033</codBanco>
          <codConv>' . self::$faker->regexify("[0-9]{9}") . '</codConv>
        </convenio>
        <descricaoErro>00000 - Título registrado em cobrança</descricaoErro>
        <dtNsu>' . $dataFake . '</dtNsu>
        <estacao>' . self::$faker->regexify("[A-Z0-9]{9}") . '</estacao>
        <nsu>TST' . mt_rand(100000, 999999) . '</nsu>
        <pagador>
          <bairro>' . self::$faker->city . '</bairro>
          <cep>' . self::$faker->postcode . '</cep>
          <cidade>' . self::$faker->city . '</cidade>
          <ender>' . self::$faker->streetAddress . '</ender>
          <nome>' . self::$faker->name . '</nome>
          <numDoc>' . self::$faker->regexify("[0-9]{15}") . '</numDoc>
          <tpDoc>01</tpDoc>
          <uf>SP</uf>
        </pagador>
        <situacao>0</situacao>
        <titulo>
          <aceito></aceito>
          <cdBarra>' . self::$faker->regexify("[0-9]{44}") . '</cdBarra>
          <dtEmissao>' . $dataFake . '</dtEmissao>
          <dtEntr>' . $dataFake . '</dtEntr>
          <dtLimiDesc>' . $dataFake . '</dtLimiDesc>
          <dtVencto>' . $dataFake . '</dtVencto>
          <especie>99</especie>
          <linDig>' . self::$faker->regexify("[0-9]{47}") . '</linDig>
          <mensagem>' . self::$faker->text . '</mensagem>
          <nossoNumero>' . self::$faker->regexify("[0-9]{13}") . '</nossoNumero>
          <pcJuro>00000</pcJuro>
          <pcMulta>00000</pcMulta>
          <qtDiasBaixa>00</qtDiasBaixa>
          <qtDiasMulta>00</qtDiasMulta>
          <qtDiasProtesto>00</qtDiasProtesto>
          <seuNumero>123456</seuNumero>
          <tpDesc>0</tpDesc>
          <tpProtesto>0</tpProtesto>
          <vlAbatimento>000000000000000</vlAbatimento>
          <vlDesc>000000000000000</vlDesc>
          <vlNominal>000000000000001</vlNominal>
        </titulo>
        <tpAmbiente>T</tpAmbiente>
      </return>
    </dlwmin:registraTituloResponse>
  </soapenv:Body>
</soapenv:Envelope>
');

        $svc = new BoletoSantanderServico($comunicador);
        $resultado = $svc->incluirTitulo(self::$ticket);

        $this->assertTrue($resultado);
    }

}
