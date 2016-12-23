<?php

/*
 * Copyright 2016 Denys.
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

use TIExpert\WSBoletoSantander\Config;
use TIExpert\WSBoletoSantander\Ticket;

class TicketTest extends PHPUnit_Framework_TestCase {

    private static $faker;
    private static $ticket;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$faker = Faker\Factory::create("pt_BR");
        self::$ticket = new Ticket();
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeConstrutorPadrao() {
        $obj = new Ticket();

        $dtTeste = new \DateTime();

        $this->assertEquals($dtTeste, $obj->getData(), "", 3600);
        $this->assertEquals(Config::getInstance()->getGeral("ambiente"), $obj->getAmbiente());
        $this->assertEquals(Config::getInstance()->getGeral("estacao"), $obj->getEstacao());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNsuQuandoOAmbienteForTeste() {
        $validParam = mt_rand(1000000000, 9999999999);

        self::$ticket->setAmbiente("T");
        self::$ticket->setNsu($validParam);

        $validParam = "TST" . $validParam;

        $this->assertEquals($validParam, self::$ticket->getNsu());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNsuQuandoOAmbienteForProducao() {
        $validParam = mt_rand(1000000000, 9999999999);

        self::$ticket->setAmbiente("P");
        self::$ticket->setNsu($validParam);

        $this->assertEquals($validParam, self::$ticket->getNsu());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeData() {
        $validParam = new \DateTime();

        self::$ticket->setData($validParam);

        $this->assertEquals($validParam, self::$ticket->getData());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeAmbiente() {
        $validParam = "P";

        self::$ticket->setAmbiente($validParam);

        $this->assertEquals($validParam, self::$ticket->getAmbiente());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeEstacao() {
        $validParam = self::$faker->regexify('[A-Z0-9]{5}');

        self::$ticket->setEstacao($validParam);

        $this->assertEquals($validParam, self::$ticket->getEstacao());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeAutenticacao() {
        $validParam = self::$faker->regexify('[A-Za-z0-9/=]{100}');

        self::$ticket->setAutenticacao($validParam);

        $this->assertEquals($validParam, self::$ticket->getAutenticacao());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aClasseDeveSerExportavelParaXmlDeAcordoComADocumentacao() {
        $xmlEstrutura = array("nsu" => self::$ticket->getNsu(),
            "dtNsu" => self::$ticket->getData()->format(Config::getInstance()->getGeral("formato_data")),
            "tpAmbiente" => self::$ticket->getAmbiente(),
            "estacao" => self::$ticket->getEstacao(),
            "ticket" => self::$ticket->getAutenticacao());

        $xmlString = self::$ticket->exportarParaXml("ticketRootNode");

        $dom = DOMDocument::loadXML($xmlString);

        foreach ($xmlEstrutura as $seletor => $conteudo) {
            $this->assertEquals($conteudo, $dom->getElementsByTagName($seletor)->item(0)->nodeValue);
        }
    }

}
