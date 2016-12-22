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

use TIExpert\WSBoletoSantander\Pagador;

/**
 * Testes unitários para a classe Pagador
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class PagadorTest extends PHPUnit_Framework_TestCase {

    private static $faker;
    private static $pagadorObj;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();

        self::$faker = \Faker\Factory::create("pt_BR");

        self::$pagadorObj = new Pagador();
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeConstrutorComTodosOsParametrosInformados() {
        $tipoDoc = 1;
        $numeroDoc = mt_rand(100000, 999999);
        $nome = self::$faker->name;
        $endereco = self::$faker->streetAddress;
        $bairro = self::$faker->city;
        $cidade = self::$faker->city;
        $UF = self::$faker->stateAbbr;
        $CEP = self::$faker->postcode;

        $obj = new Pagador($tipoDoc, $numeroDoc, $nome, $endereco, $bairro, $cidade, $UF, $CEP);

        $this->assertEquals($tipoDoc, $obj->getTipoDoc());
        $this->assertEquals($numeroDoc, $obj->getNumeroDoc());
        $this->assertEquals($nome, $obj->getNome());
        $this->assertEquals($endereco, $obj->getEndereco());
        $this->assertEquals($bairro, $obj->getBairro());
        $this->assertEquals($cidade, $obj->getCidade());
        $this->assertEquals($UF, $obj->getUF());
        $this->assertEquals($CEP, $obj->getCEP());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeBairro() {
        $validParam = self::$faker->city;

        self::$pagadorObj->setBairro($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getBairro());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeCEP() {
        $validParam = self::$faker->postcode;

        self::$pagadorObj->setCEP($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getCEP());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeCidade() {
        $validParam = self::$faker->city;

        self::$pagadorObj->setCidade($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getCidade());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeEndereco() {
        $validParam = self::$faker->streetAddress;

        self::$pagadorObj->setEndereco($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getEndereco());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNome() {
        $validParam = self::$faker->name;

        self::$pagadorObj->setNome($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getNome());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNumeroDoc() {
        $validParam = "123456789";

        self::$pagadorObj->setNumeroDoc($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getNumeroDoc());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTipoDoc() {
        $validParam = 1;

        self::$pagadorObj->setTipoDoc($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getTipoDoc());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeUF() {
        $validParam = self::$faker->stateAbbr;

        self::$pagadorObj->setUF($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getUF());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function oArrayExportadoDevePossuirAsMesmasChavesUtilizadasPeloWSdoBanco() {
        $chavePagador = array("PAGADOR.TP-DOC", "PAGADOR.NUM-DOC", "PAGADOR.NOME", "PAGADOR.ENDER", "PAGADOR.BAIRRO", "PAGADOR.CIDADE", "PAGADOR.UF", "PAGADOR.CEP");

        $export = self::$pagadorObj->exportarArray();

        foreach ($chavePagador as $chave) {
            $this->assertArrayHasKey($chave, $export);
        }
    }

}
