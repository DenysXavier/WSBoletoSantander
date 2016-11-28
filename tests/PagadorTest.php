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

    private static $pagadorObj;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$pagadorObj = new Pagador();
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeBairro() {
        $validParam = "Centro";

        self::$pagadorObj->setBairro($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getBairro());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeCEP() {
        $validParam = "01001-001";

        self::$pagadorObj->setCEP($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getCEP());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeCidade() {
        $validParam = "São Paulo";

        self::$pagadorObj->setCidade($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getCidade());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeEndereco() {
        $validParam = "Praça da Sé, S/N";

        self::$pagadorObj->setEndereco($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getEndereco());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNome() {
        $validParam = "Fulano da Silva";

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
        $validParam = "SP";

        self::$pagadorObj->setUF($validParam);
        $this->assertEquals($validParam, self::$pagadorObj->getUF());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function oArrayExportadoDevePossuirAsMesmasChavesUtilizadasPeloWSdoBanco() {
        $chavePagador = array("PAGADOR.TP-DOC", "PAGADOR.NUM-DOC", "PAGADOR.NOME", "PAGADOR.ENDER", "PAGADOR.BAIRRO", "PAGADOR.CIDADE", "PAGADOR.UF", "PAGADOR.CEP");

        $pagadorObj = new Pagador();
        $export = $pagadorObj->exportarArray();

        foreach ($chavePagador as $chave) {
            $this->assertArrayHasKey($chave, $export);
        }
    }

}
