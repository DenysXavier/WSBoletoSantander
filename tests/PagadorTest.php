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

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeBairro() {
        $validParam = "Centro";

        $obj = new Pagador();
        $obj->setBairro($validParam);
        $this->assertEquals($validParam, $obj->getBairro());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeCEP() {
        $validParam = "01001-001";

        $obj = new Pagador();
        $obj->setCEP($validParam);
        $this->assertEquals($validParam, $obj->getCEP());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeCidade() {
        $validParam = "São Paulo";

        $obj = new Pagador();
        $obj->setCidade($validParam);
        $this->assertEquals($validParam, $obj->getCidade());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeEndereco() {
        $validParam = "Praça da Sé, S/N";

        $obj = new Pagador();
        $obj->setEndereco($validParam);
        $this->assertEquals($validParam, $obj->getEndereco());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNome() {
        $validParam = "Fulano da Silva";

        $obj = new Pagador();
        $obj->setNome($validParam);
        $this->assertEquals($validParam, $obj->getNome());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNumeroDoc() {
        $validParam = "123456789";

        $obj = new Pagador();
        $obj->setNumeroDoc($validParam);
        $this->assertEquals($validParam, $obj->getNumeroDoc());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeTipoDoc() {
        $validParam = 1;

        $obj = new Pagador();
        $obj->setTipoDoc($validParam);
        $this->assertEquals($validParam, $obj->getTipoDoc());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeUF() {
        $validParam = "SP";

        $obj = new Pagador();
        $obj->setUF($validParam);
        $this->assertEquals($validParam, $obj->getUF());
    }

}
