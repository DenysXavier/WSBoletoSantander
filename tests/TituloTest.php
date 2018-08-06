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

    private static $tituloObj;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        self::$tituloObj = new Titulo(100, "000000000");
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function osValoresPadroesParaDataDeEmissaoEDataDeVencimentoSaoOProprioDia() {
        $obj = new Titulo();

        $dataEsperada = new \DateTime();

        $this->assertEquals($dataEsperada->format("Y-m-d"), $obj->getDataEmissao()->format("Y-m-d"));
        $this->assertEquals($dataEsperada->format("Y-m-d"), $obj->getDataVencimento()->format("Y-m-d"));
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeNossoNumero() {
        $validParam = "123456789";
        self::$tituloObj->setNossoNumero($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getNossoNumero());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeSeuNumero() {
        $validParam = "0123456789ABCDE";
        self::$tituloObj->setSeuNumero($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getSeuNumero());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeDataVencimento() {
        $validParam = new \DateTime("2016-11-28");
        self::$tituloObj->setDataVencimento($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getDataVencimento());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function testeAcessorDaPropriedadeDataVencimentoComParametroInvalido() {
        $invalidParam = array();
        self::$tituloObj->setDataVencimento($invalidParam);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeDataEmissao() {
        $validParam = new \DateTime("2016-11-28");
        self::$tituloObj->setDataEmissao($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getDataEmissao());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException \Exception
     */
    public function testeAcessorDaPropriedadeDataEmissaoComParametroInvalido() {
        $invalidParam = array();
        self::$tituloObj->setDataEmissao($invalidParam);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeEspecie() {
        $validParam = 99;
        self::$tituloObj->setEspecie($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getEspecie());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeValor() {
        $validParam = 25.50;
        self::$tituloObj->setValor($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getValor());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeMensagem() {
        $validParam = "Mensagem de teste\r\nLinha 1\r\nLinha 2\r\n...";
        self::$tituloObj->setMensagem($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getMensagem());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function testeAcessorDaPropriedadeInstrucoes() {
        $validParam = new InstrucoesDeTitulo();
        self::$tituloObj->setInstrucoes($validParam);
        $this->assertEquals($validParam, self::$tituloObj->getInstrucoes());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function oArrayExportadoDevePossuirAsMesmasChavesUtilizadasPeloWSdoBanco() {
        $chaveTitulo = array("TITULO.NOSSO-NUMERO", "TITULO.SEU-NUMERO", "TITULO.DT-VENCTO", "TITULO.DT-EMISSAO", "TITULO.ESPECIE", "TITULO.VL-NOMINAL", "MENSAGEM");

        $export = self::$tituloObj->exportarArray();

        foreach ($chaveTitulo as $chave) {
            $this->assertArrayHasKey($chave, $export);
        }
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function oValorMonetarioDaChaveTITULO_VL_NOMINALNaoPossuiVirgula() {
        $valorNominal = 100.123;
        $valorExportado = 10012;

        $titulo = new Titulo($valorNominal);
        $export = $titulo->exportarArray();

        $this->assertEquals($valorExportado, $export["TITULO.VL-NOMINAL"]);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function calculoDeDigitoVerificadorDeNossoNumeroComMenosDe8Algarismos() {
        $nossoNumero = 12345;
        $nossoNumeroComDigito = 123455;

        $titulo = new Titulo();
        $titulo->setNossoNumero($nossoNumero);

        $this->assertEquals($nossoNumeroComDigito, $titulo->getNossoNumeroComDigito());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function calculoDeDigitoVerificadorDeNossoNumeroComMaisDe8Algarismos() {
        $nossoNumero = 123456789012;
        $nossoNumeroComDigito = 1234567890123;

        $titulo = new Titulo();
        $titulo->setNossoNumero($nossoNumero);

        $this->assertEquals($nossoNumeroComDigito, $titulo->getNossoNumeroComDigito());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function sempreQueOModulo11DaSomatoriaDosAlgarismosForMenorQue1EntaoDeveRetornarZero() {
        $titulo = new Titulo();

        $nossoNumeroModulo11Igual0 = 2023;
        $nossoNumeroModulo11Igual0ComDigito = 20230;

        $titulo->setNossoNumero($nossoNumeroModulo11Igual0);
        $this->assertEquals($nossoNumeroModulo11Igual0ComDigito, $titulo->getNossoNumeroComDigito());

        $nossoNumeroModulo11Igual1 = 2001;
        $nossoNumeroModulo11Igual1ComDigito = 20010;

        $titulo->setNossoNumero($nossoNumeroModulo11Igual1);
        $this->assertEquals($nossoNumeroModulo11Igual1ComDigito, $titulo->getNossoNumeroComDigito());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoSondarUmTituloODigitoVerificadorDoNossoNumeroDeveSerRemovido() {
        $nossoNumeroSondado = "1234567890123";
        $nossoNumeroEsperado = "123456789012";

        $xml = new DOMDocument();
        $xml->loadXML("<titulo>
          <aceito></aceito>
          <cdBarra></cdBarra>
          <dtEmissao>17072017</dtEmissao>
          <dtEntr>17072017</dtEntr>
          <dtLimiDesc>17072017</dtLimiDesc>
          <dtVencto>30072017</dtVencto>
          <especie>099</especie>
          <linDig></linDig>
          <mensagem></mensagem>
          <nossoNumero>" . $nossoNumeroSondado . "</nossoNumero>
          <pcJuro>00000</pcJuro>
          <pcMulta>00000</pcMulta>
          <qtDiasBaixa>00</qtDiasBaixa>
          <qtDiasMulta>00</qtDiasMulta>
          <qtDiasProtesto>00</qtDiasProtesto>
          <seuNumero>000000000123456</seuNumero>
          <tpDesc>0</tpDesc>
          <tpProtesto>0</tpProtesto>
          <tipoValor>0</tipoValor>
          <tipoPagto>1</tipoPagto>
          <qtdParciais>00</qtdParciais>
          <valorMaximo>00000000000000000</valorMaximo>
          <valorMinimo>00000000000000000</valorMinimo>
          <vlAbatimento>000000000000000</vlAbatimento>
          <vlDesc>000000000000000</vlDesc>
          <vlNominal>000000000000110</vlNominal>
        </titulo>");

        $titulo = new Titulo();
        $titulo->carregarPorXML($xml);

        $this->assertEquals($nossoNumeroEsperado, $titulo->getNossoNumero());
    }

}
