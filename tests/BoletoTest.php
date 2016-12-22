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

use TIExpert\WSBoletoSantander\Convenio;
use TIExpert\WSBoletoSantander\Pagador;
use TIExpert\WSBoletoSantander\Titulo;
use TIExpert\WSBoletoSantander\InstrucoesDeTitulo;
use TIExpert\WSBoletoSantander\Boleto;
use Faker\Factory;

class BoletoTest extends PHPUnit_Framework_TestCase {

    private static $boletoObj;
    private static $convenio;
    private static $pagador;
    private static $titulo;
    private static $faker;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        self::$faker = Factory::create("pt_BR");

        self::$convenio = new Convenio("0033", mt_rand(1000000, 9999999));
        self::$pagador = new Pagador(1, mt_rand(1000000, 9999999), self::$faker->name, self::$faker->streetAddress, self::$faker->city, self::$faker->city, self::$faker->stateAbbr, self::$faker->postcode);
        self::$titulo = new Titulo(mt_rand(0, 9999999), mt_rand(100000000, 999999999), mt_rand(100000000, 999999999), "now", NULL, NULL, NULL, new InstrucoesDeTitulo());
        self::$boletoObj = new Boleto(new Convenio(), new Pagador(), new Titulo());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeConstrutorBoleto() {
        $faker = Factory::create("pt_BR");

        $convenio = new Convenio(mt_rand(0, 9999), mt_rand(1000000, 9999999));
        $pagador = new Pagador(1, mt_rand(1000000, 9999999), $faker->name, $faker->streetAddress, $faker->city, $faker->city, $faker->stateAbbr, $faker->postcode);
        $titulo = new Titulo(mt_rand(0, 9999999), mt_rand(100000000, 999999999), mt_rand(100000000, 999999999), "now");

        $obj = new Boleto($convenio, $pagador, $titulo);

        $this->assertEquals($convenio, $obj->getConvenio());
        $this->assertEquals($pagador, $obj->getPagador());
        $this->assertEquals($titulo, $obj->getTitulo());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeConvenio() {
        self::$boletoObj->setConvenio(self::$convenio);
        $this->assertEquals(self::$convenio, self::$boletoObj->getConvenio());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadePagador() {
        self::$boletoObj->setPagador(self::$pagador);
        $this->assertEquals(self::$pagador, self::$boletoObj->getPagador());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTitulo() {
        self::$boletoObj->setTitulo(self::$titulo);
        $this->assertEquals(self::$titulo, self::$boletoObj->getTitulo());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function oArrayExportadoDevePossuirAsMesmasChavesUtilizadasPeloWSdoBanco() {
        $chaveBoletoBanco = array("CONVENIO.COD-BANCO",
            "CONVENIO.COD-CONVENIO",
            "PAGADOR.TP-DOC",
            "PAGADOR.NUM-DOC",
            "PAGADOR.NOME",
            "PAGADOR.ENDER",
            "PAGADOR.BAIRRO",
            "PAGADOR.CIDADE",
            "PAGADOR.UF",
            "PAGADOR.CEP",
            "TITULO.NOSSO-NUMERO",
            "TITULO.SEU-NUMERO",
            "TITULO.DT-VENCTO",
            "TITULO.DT-EMISSAO",
            "TITULO.ESPECIE",
            "TITULO.VL-NOMINAL",
            "MENSAGEM",
            "TITULO.PC-MULTA",
            "TITULO.QT-DIAS-MULTA",
            "TITULO.PC-JURO",
            "TITULO.TP-DESC",
            "TITULO.VL-DESC",
            "TITULO.DT-LIMI-DESC",
            "TITULO.VL-ABATIMENTO",
            "TITULO.TP-PROTESTO",
            "TITULO.QT-DIAS-PROTESTO",
            "TITULO.QT-DIAS-BAIXA");

        $export = self::$boletoObj->exportarArray();

        foreach ($chaveBoletoBanco as $chave) {
            $this->assertArrayHasKey($chave, $export);
        }
    }

}
