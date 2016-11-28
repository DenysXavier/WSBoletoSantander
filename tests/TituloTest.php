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

use TIExpert\WSBoletoSantander\Titulo;
use TIExpert\WSBoletoSantander\InstrucoesDeTitulo;

/**
 * Testes unitários para a classe Pagador
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class TituloTest extends PHPUnit_Framework_TestCase {

    private $tituloObj;

    protected function setUp() {
        parent::setUp();
        $this->tituloObj = new Titulo(100, "000000000");
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function osValoresPadroesParaDataDeEmissaoEDataDeVencimentoSaoOProprioDia() {
        $dataEsperada = new \DateTime();

        $this->assertEquals($dataEsperada, $this->tituloObj->getDataEmissao());
        $this->assertEquals($dataEsperada, $this->tituloObj->getDataVencimento());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNossoNumero() {
        $validParam = "123456789";
        $this->tituloObj->setNossoNumero($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getNossoNumero());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeSeuNumero() {
        $validParam = "0123456789ABCDE";
        $this->tituloObj->setSeuNumero($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getSeuNumero());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeDataVencimento() {
        $validParam = new \DateTime("2016-11-28");
        $this->tituloObj->setDataVencimento($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getDataVencimento());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function testeAcessorDaPropriedadeDataVencimentoComParametroInvalido() {
        $invalidParam = array();
        $this->tituloObj->setDataVencimento($invalidParam);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeDataEmissao() {
        $validParam = new \DateTime("2016-11-28");
        $this->tituloObj->setDataEmissao($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getDataEmissao());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function testeAcessorDaPropriedadeDataEmissaoComParametroInvalido() {
        $invalidParam = array();
        $this->tituloObj->setDataEmissao($invalidParam);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeEspecie() {
        $validParam = 99;
        $this->tituloObj->setEspecie($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getEspecie());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeValor() {
        $validParam = 25.50;
        $this->tituloObj->setValor($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getValor());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeMensagem() {
        $validParam = "Mensagem de teste\r\nLinha 1\r\nLinha 2\r\n...";
        $this->tituloObj->setMensagem($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getMensagem());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeInstrucoes() {
        $validParam = new InstrucoesDeTitulo();
        $this->tituloObj->setInstrucoes($validParam);
        $this->assertEquals($validParam, $this->tituloObj->getInstrucoes());
    }

}
