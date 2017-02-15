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

use TIExpert\WSBoletoSantander\Boleto;
use TIExpert\WSBoletoSantander\BoletoSantanderServico;
use TIExpert\WSBoletoSantander\Config;
use TIExpert\WSBoletoSantander\Convenio;
use TIExpert\WSBoletoSantander\InstrucoesDeTitulo;
use TIExpert\WSBoletoSantander\Pagador;
use TIExpert\WSBoletoSantander\Ticket;
use TIExpert\WSBoletoSantander\Titulo;

class BoletoSantanderServicoTest extends PHPUnit_Framework_TestCase {

    private static $ticket;
    private static $boleto;
    private static $faker;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$faker = Faker\Factory::create("pt_BR");

        $convenio = new Convenio();
        $pagador = new Pagador(1, mt_rand(100000, 999999), self::$faker->name, self::$faker->streetAddress, self::$faker->city, self::$faker->city, self::$faker->stateAbbr, self::$faker->postcode);
        $titulo = new Titulo(mt_rand(0, 9999999), mt_rand(100000000, 999999999), mt_rand(100000000, 999999999), "now", NULL, NULL, NULL, new InstrucoesDeTitulo());

        self::$boleto = new Boleto($convenio, $pagador, $titulo);

        self::$ticket = new Ticket();
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
        $dataFake = date(Config::getInstance()->getGeral("formato_data"));

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
        <estacao>' . self::$faker->regexify("[A-Z0-9]{4}") . '</estacao>
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

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeParaSondarUmTitulo() {
        $formato_data = Config::getInstance()->getGeral("formato_data");

        $comunicador = $this->getMockBuilder("TIExpert\WSBoletoSantander\ComunicadorCurlSOAP")->getMock();
        $comunicador->method("chamar")->willReturn('<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <dlwmin:consultaTituloResponse xmlns:dlwmin="http://impl.webservice.ymb.app.bsbr.altec.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <return xmlns:ns2="http://impl.webservice.ymb.app.bsbr.altec.com/">
        <codcede>' . self::$faker->regexify("[0-9]{9}") . '</codcede>
        <convenio>
          <codBanco>' . self::$boleto->getConvenio()->getCodigoBanco() . '</codBanco>
          <codConv>' . self::$boleto->getConvenio()->getCodigoConvenio() . '</codConv>
        </convenio>
        <descricaoErro></descricaoErro>
        <dtNsu>10022017</dtNsu>
        <estacao>' . self::$faker->regexify("[A-Z0-9]{4}") . '</estacao>
        <nsu>TST' . self::$faker->regexify("[0-9]{4}") . '</nsu>
        <pagador>
          <bairro>' . self::$boleto->getPagador()->getBairro() . '</bairro>
          <cep>' . self::$boleto->getPagador()->getCEP() . '</cep>
          <cidade>' . self::$boleto->getPagador()->getCidade() . '</cidade>
          <ender>' . self::$boleto->getPagador()->getEndereco() . '</ender>
          <nome>' . self::$boleto->getPagador()->getNome() . '</nome>
          <numDoc>' . self::$boleto->getPagador()->getNumeroDoc() . '</numDoc>
          <tpDoc>' . self::$boleto->getPagador()->getTipoDoc() . '</tpDoc>
          <uf>' . self::$boleto->getPagador()->getUF() . '</uf>
        </pagador>
        <situacao>0</situacao>
        <titulo>
          <aceito></aceito>
          <cdBarra>' . self::$faker->regexify("[0-9]{44}") . '</cdBarra>
          <dtEmissao>' . self::$boleto->getTitulo()->getDataEmissao()->format($formato_data) . '</dtEmissao>
          <dtEntr>' . date($formato_data) . '</dtEntr>
          <dtLimiDesc>' . self::$boleto->getTitulo()->getInstrucoes()->getDataLimiteDesconto()->format($formato_data) . '</dtLimiDesc>
          <dtVencto>' . self::$boleto->getTitulo()->getDataVencimento()->format($formato_data) . '</dtVencto>
          <especie>' . self::$boleto->getTitulo()->getEspecie() . '</especie>
          <linDig>' . self::$faker->regexify("[0-9]{47}") . '</linDig>
          <mensagem></mensagem>
          <nossoNumero>' . self::$boleto->getTitulo()->getNossoNumero() . '</nossoNumero>
          <pcJuro>' . self::$boleto->getTitulo()->getInstrucoes()->getJuros() . '</pcJuro>
          <pcMulta>' . self::$boleto->getTitulo()->getInstrucoes()->getMulta() . '</pcMulta>
          <qtDiasBaixa>' . self::$boleto->getTitulo()->getInstrucoes()->getBaixarApos() . '</qtDiasBaixa>
          <qtDiasMulta>' . self::$boleto->getTitulo()->getInstrucoes()->getMultarApos() . '</qtDiasMulta>
          <qtDiasProtesto>' . self::$boleto->getTitulo()->getInstrucoes()->getProtestarApos() . '</qtDiasProtesto>
          <seuNumero>' . self::$boleto->getTitulo()->getSeuNumero() . '</seuNumero>
          <tpDesc>' . self::$boleto->getTitulo()->getInstrucoes()->getTipoDesconto() . '</tpDesc>
          <tpProtesto>' . self::$boleto->getTitulo()->getInstrucoes()->getTipoProtesto() . '</tpProtesto>
          <vlAbatimento>' . self::$boleto->getTitulo()->getInstrucoes()->getValorAbatimento() * 100 . '</vlAbatimento>
          <vlDesc>' . self::$boleto->getTitulo()->getInstrucoes()->getValorDesconto() * 100 . '</vlDesc>
          <vlNominal>' . self::$boleto->getTitulo()->getValor() * 100 . '</vlNominal>
        </titulo>
        <tpAmbiente>T</tpAmbiente>
      </return>
    </dlwmin:consultaTituloResponse>
  </soapenv:Body>
</soapenv:Envelope>');

        $svc = new BoletoSantanderServico($comunicador);
        $resultado = $svc->sondarTitulo(self::$ticket);

        $this->assertEquals(self::$boleto, $resultado, '', 60);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function seOBoletoSondadoNaoExistirEntaoUmaExcecaoDeveSerLancada() {
        $comunicador = $this->getMockBuilder("TIExpert\WSBoletoSantander\ComunicadorCurlSOAP")->getMock();
        $comunicador->method("chamar")->willReturn('<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <dlwmin:consultaTituloResponse xmlns:dlwmin="http://impl.webservice.ymb.app.bsbr.altec.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <return xmlns:ns2="http://impl.webservice.ymb.app.bsbr.altec.com/">
        <codcede></codcede>
        <convenio>
          <codBanco>0033</codBanco>
          <codConv>' . self::$faker->regexify('[0-9]{9}') . '</codConv>
        </convenio>
        <descricaoErro>@ERYKE0411 - TITULO NAO ENCONTRADO</descricaoErro>
        <dtNsu>' . date(Config::getInstance()->getGeral("formato_data")) . '</dtNsu>
        <estacao>' . self::$faker->regexify("[A-Z0-9]{4}") . '</estacao>
        <nsu>TST' . self::$faker->regexify("[0-9]{4}") . '</nsu>
        <pagador>
          <bairro></bairro>
          <cep></cep>
          <cidade></cidade>
          <ender></ender>
          <nome></nome>
          <numDoc></numDoc>
          <tpDoc></tpDoc>
          <uf></uf>
        </pagador>
        <situacao>20</situacao>
        <titulo>
          <aceito></aceito>
          <cdBarra></cdBarra>
          <dtEmissao></dtEmissao>
          <dtEntr></dtEntr>
          <dtLimiDesc></dtLimiDesc>
          <dtVencto></dtVencto>
          <especie></especie>
          <linDig></linDig>
          <mensagem></mensagem>
          <nossoNumero></nossoNumero>
          <pcJuro></pcJuro>
          <pcMulta></pcMulta>
          <qtDiasBaixa></qtDiasBaixa>
          <qtDiasMulta></qtDiasMulta>
          <qtDiasProtesto></qtDiasProtesto>
          <seuNumero></seuNumero>
          <tpDesc></tpDesc>
          <tpProtesto></tpProtesto>
          <vlAbatimento></vlAbatimento>
          <vlDesc></vlDesc>
          <vlNominal></vlNominal>
        </titulo>
        <tpAmbiente>T</tpAmbiente>
      </return>
    </dlwmin:consultaTituloResponse>
  </soapenv:Body>
</soapenv:Envelope>
');

        $svc = new BoletoSantanderServico($comunicador);
        $svc->sondarTitulo(self::$ticket);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function incluirUmTituloBaseadoEmUmTicketDeBoletoIncorretoDeveLancarUmaExcecao() {
        $hoje = date(Config::getInstance()->getGeral("formato_data"));

        $comunicador = $this->getMockBuilder("TIExpert\WSBoletoSantander\ComunicadorCurlSOAP")->getMock();
        $comunicador->method("chamar")->willReturn('<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <dlwmin:registraTituloResponse xmlns:dlwmin="http://impl.webservice.ymb.app.bsbr.altec.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <return xmlns:ns2="http://impl.webservice.ymb.app.bsbr.altec.com/">
        <codcede></codcede>
        <convenio>
          <codBanco>0033</codBanco>
          <codConv>' . self::$faker->regexify('[0-9]{9}') . '</codConv>
        </convenio>
        <descricaoErro>00058-CPF / CNPJ INCORRETO</descricaoErro>
        <dtNsu>' . $hoje . '</dtNsu>
        <estacao>' . self::$faker->regexify("[A-Z0-9]{4}") . '</estacao>
        <nsu>TST' . self::$faker->regexify("[0-9]{4}") . '</nsu>
        <pagador>
          <bairro>' . self::$faker->city . '</bairro>
          <cep>' . self::$faker->postcode . '</cep>
          <cidade>' . self::$faker->city . '</cidade>
          <ender>' . self::$faker->streetAddress . '</ender>
          <nome>' . self::$faker->name . '</nome>
          <numDoc>00000000000</numDoc>
          <tpDoc>01</tpDoc>
          <uf>SP</uf>
        </pagador>
        <situacao>20</situacao>
        <titulo>
          <aceito></aceito>
          <cdBarra></cdBarra>
          <dtEmissao>' . $hoje . '</dtEmissao>
          <dtEntr></dtEntr>
          <dtLimiDesc>' . $hoje . '</dtLimiDesc>
          <dtVencto>' . $hoje . '</dtVencto>
          <especie>99</especie>
          <linDig></linDig>
          <mensagem>' . self::$faker->text . '</mensagem>
          <nossoNumero>' . self::$faker->regexify("[0-9]{13}") . '</nossoNumero>
          <pcJuro>00000</pcJuro>
          <pcMulta>00000</pcMulta>
          <qtDiasBaixa>00</qtDiasBaixa>
          <qtDiasMulta>00</qtDiasMulta>
          <qtDiasProtesto>00</qtDiasProtesto>
          <seuNumero>' . self::$faker->regexify("[0-9]{15}") . '</seuNumero>
          <tpDesc>0</tpDesc>
          <tpProtesto>0</tpProtesto>
          <vlAbatimento>000000000000000</vlAbatimento>
          <vlDesc>000000000000000</vlDesc>
          <vlNominal>000000000000110</vlNominal>
        </titulo>
        <tpAmbiente>T</tpAmbiente>
      </return>
    </dlwmin:registraTituloResponse>
  </soapenv:Body>
</soapenv:Envelope>
');

        $svc = new BoletoSantanderServico($comunicador);
        $svc->incluirTitulo(self::$ticket);
    }

    /**
     * @author Denys Xavier <equipe@tiexpet.net>
     * @test
     * @expectedException \Exception
     */
    public function xmlDeTicketRetornadoComNodeRetcodeDiferenteDeZeroDeveLancarUmaExcecao() {
        $comunicador = $this->getMockBuilder("TIExpert\WSBoletoSantander\ComunicadorCurlSOAP")->getMock();
        $comunicador->method("chamar")->willReturn('<?xml version="1.0" encoding="utf-8"?>
<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">
  <soapenv:Body>
    <dlwmin:createResponse xmlns:dlwmin="http://impl.webservice.dl.app.bsbr.altec.com/" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
      <TicketResponse>
        <retCode>4</retCode>
        <ticket>' . self::$faker->regexify("[A-Za-z0-9+/]{48}") . '</ticket>
      </TicketResponse>
    </dlwmin:createResponse>
  </soapenv:Body>
</soapenv:Envelope>');

        $svc = new BoletoSantanderServico($comunicador);
        $svc->solicitarTicketInclusao(self::$boleto);
    }

}
