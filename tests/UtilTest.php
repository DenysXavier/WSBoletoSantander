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

use TIExpert\WSBoletoSantander\Util;

/**
 * Testes unitários para a classe Util
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class UtilTest extends \PHPUnit_Framework_TestCase {

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function umaStringDeDataFormatadaCorretamenteDeveGerarUmObjetoDateTime() {
        $str = date(\TIExpert\WSBoletoSantander\Config::getInstance()->getGeral("formato_data"));

        $dataEsperada = new \DateTime();
        $dataEsperada->setDate(date("Y"), date("m"), date("d"));

        $resultado = Util::converterParaDateTime($str);

        $this->assertEquals($dataEsperada, $resultado, '', 60);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function seOParametroUsadoAoConverterParaDateTimeForTambemDateTimeEntaoOProprioObjetoDeveSerRetornado() {
        $dataEsperada = new \DateTime("2016-11-28 12:00:00");

        $resultado = Util::converterParaDateTime($dataEsperada);

        $this->assertEquals($dataEsperada, $resultado, '', 60);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function seOParametroUsadoAoConverterParaDateTimeForNuloEntaoOProprioParametroEhRetornado() {
        $this->assertNull(Util::converterParaDateTime(NULL));
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException InvalidArgumentException
     */
    public function seOParametroUsadoAoConverterParaDateTimeNaoForStringOuDateTimeEntaoUmaExcecaoDeveSerLancada() {
        $array = array("2016-11-28");

        Util::converterParaDateTime($array);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException Exception
     */
    public function umaStringDeDataFormatadaIncorretamenteDeveLancarUmaExcecao() {
        $string = "11#28#2016";

        Util::converterParaDateTime($string);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function umNumeroFormatadoComZeroPrecisaoSempreSeraApenasSuaParteInteira() {
        $float = 12.25;
        $resultadoEsperado = "12";

        $stringFormatada = Util::formatarNumero($float, 0);

        $this->assertEquals($resultadoEsperado, $stringFormatada);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoFormatarUmNumeroComGrandePrecisaoAParteDecimalNaoDeveSerArredondada() {
        $bigFloat = 1.789;
        $resultadoEsperado = "1.78";

        $stringFormatada = Util::formatarNumero($bigFloat, 2, '.');

        $this->assertEquals($resultadoEsperado, $stringFormatada);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoFormatarUmNumeroComBaixaPrecisaoAParteDecimalDeveSerCompletadaComZeros() {
        $smallFloat = 1.789;
        $resultadoEsperado = "1.78900";

        $stringFormatada = Util::formatarNumero($smallFloat, 5, '.');

        $this->assertSame($resultadoEsperado, $stringFormatada);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoFormatarUmNumeroMaiorQue999ACada3AlgarismosDeveVirUmSeparadorDeterminado() {
        $bigNumber = 12345678;
        $resultadoEsperado = "12@345@678";

        $stringFormatada = Util::formatarNumero($bigNumber, 0, '.', '@');

        $this->assertEquals($resultadoEsperado, $stringFormatada);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function aoFormatarUmNumeroInteiroAPrecisaoDeveVirCompostaDeZeros() {
        $number = 1;
        $resultadoEsperado = "1.00";

        $stringFormatada = Util::formatarNumero($number, 2, '.');

        $this->assertSame($resultadoEsperado, $stringFormatada);
    }

}
