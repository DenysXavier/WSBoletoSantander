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

/**
 * Testes unitários para a classe Convenio
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class InstrucoesDeTituloTest extends PHPUnit_Framework_TestCase {

    private $instrucoesObj;

    protected function setUp() {
        parent::setUp();
        $this->instrucoesObj = new InstrucoesDeTitulo();
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeMulta() {
        $validParam = 100.99;

        $this->instrucoesObj->setMulta($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getMulta());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeMultarApos() {
        $validParam = 99;

        $this->instrucoesObj->setMultarApos($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getMultarApos());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeJuros() {
        $validParam = 105.02;

        $this->instrucoesObj->setJuros($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getJuros());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTipoDesconto() {
        $validParam = 0;

        $this->instrucoesObj->setTipoDesconto($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getTipoDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeValorDesconto() {
        $validParam = 12000.01;

        $this->instrucoesObj->setValorDesconto($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getValorDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeDataLimiteDesconto() {
        $validParam = new \DateTime("2016-11-28");

        $this->instrucoesObj->setDataLimiteDesconto($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getDataLimiteDesconto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function testeAcessorDaPropriedadeDataLimiteDescontoComParametroInvalido() {
        $invalidParam = array();

        $this->instrucoesObj->setDataLimiteDesconto($invalidParam);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeValorAbatimento() {
        $validParam = 12000.01;

        $this->instrucoesObj->setValorAbatimento($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getValorAbatimento());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTipoProtesto() {
        $validParam = 0;

        $this->instrucoesObj->setTipoProtesto($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getTipoProtesto());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeProtestarApos() {
        $validParam = 99;

        $this->instrucoesObj->setProtestarApos($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getProtestarApos());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeBaixarApos() {
        $validParam = 99;

        $this->instrucoesObj->setBaixarApos($validParam);
        $this->assertEquals($validParam, $this->instrucoesObj->getBaixarApos());
    }

}
