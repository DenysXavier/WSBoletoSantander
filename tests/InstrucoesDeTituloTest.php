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

use TIExpert\WSBoletoSantander\InstrucoesDeTitulo;
use TIExpert\WSBoletoSantander\Config;

/**
 * Testes unitários para a classe InstrucoesDeTitulo
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class InstrucoesDeTituloTest extends PHPUnit_Framework_TestCase {

    private static $instrucoesObj;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$instrucoesObj = new InstrucoesDeTitulo();
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeMulta() {
        $validParam = 100.99;

        self::$instrucoesObj->setMulta($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getMulta());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeMultarApos() {
        $validParam = 99;

        self::$instrucoesObj->setMultarApos($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getMultarApos());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeJuros() {
        $validParam = 105.02;

        self::$instrucoesObj->setJuros($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getJuros());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTipoDesconto() {
        $validParam = 0;

        self::$instrucoesObj->setTipoDesconto($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getTipoDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoTentarSetarTipoDescontoComoNuloEntaoOValorConfiguradoDeveSerCarregado() {
        self::$instrucoesObj->setTipoDesconto(NULL);
        $this->assertEquals(Config::getInstance()->getInstrucao("tipo_desconto"), self::$instrucoesObj->getTipoDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeValorDesconto() {
        $validParam = 12000.01;

        self::$instrucoesObj->setValorDesconto($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getValorDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoTentarSetarValorDescontoComoNuloEntaoOValorConfiguradoDeveSerCarregado() {
        self::$instrucoesObj->setValorDesconto(NULL);
        $this->assertEquals(Config::getInstance()->getInstrucao("valor_desconto"), self::$instrucoesObj->getValorDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeDataLimiteDesconto() {
        $validParam = new \DateTime("2016-11-28");

        self::$instrucoesObj->setDataLimiteDesconto($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getDataLimiteDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function testeAcessorDaPropriedadeDataLimiteDescontoComParametroInvalido() {
        $invalidParam = array();

        self::$instrucoesObj->setDataLimiteDesconto($invalidParam);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoTentarSetarDataLimiteDescontoComoNuloEntaoOValorConfiguradoDeveSerCarregado() {
        self::$instrucoesObj->setDataLimiteDesconto(NULL);

        $dataEsperada = new \DateTime(Config::getInstance()->getInstrucao("data_limite_desconto"));

        $this->assertEquals($dataEsperada, self::$instrucoesObj->getDataLimiteDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeValorAbatimento() {
        $validParam = 12000.01;

        self::$instrucoesObj->setValorAbatimento($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getValorAbatimento());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTipoProtesto() {
        $validParam = 0;

        self::$instrucoesObj->setTipoProtesto($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getTipoProtesto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoTentarSetarTipoProtestoComoNuloEntaoOValorConfiguradoDeveSerCarregado() {
        self::$instrucoesObj->setTipoProtesto(NULL);
        $this->assertEquals(Config::getInstance()->getInstrucao("tipo_protesto"), self::$instrucoesObj->getTipoProtesto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeProtestarApos() {
        $validParam = 99;

        self::$instrucoesObj->setProtestarApos($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getProtestarApos());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeBaixarApos() {
        $validParam = 99;

        self::$instrucoesObj->setBaixarApos($validParam);
        $this->assertEquals($validParam, self::$instrucoesObj->getBaixarApos());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoTentarSetarBaixarAposComoNuloEntaoOValorConfiguradoDeveSerCarregado() {
        self::$instrucoesObj->setBaixarApos(NULL);
        $this->assertEquals(Config::getInstance()->getInstrucao("baixar_apos"), self::$instrucoesObj->getBaixarApos());
    }

}
