<?php

/*
 * Copyright 2016 Denys Xavier.
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

namespace TIExpert\WSBoletoSantander;

/**
 * Testes unitários para a classe Config
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class ConfigTest extends \PHPUnit_Framework_TestCase {

    private static $caminhoArquivoINI = "./config.ini";
    private $caminhoPadraoArquivoINI = "src/config.ini";
    private $configObj;

    public static function setUpBeforeClass() {
        parent::setUpBeforeClass();
        if (file_exists(self::$caminhoArquivoINI)) {
            unlink(self::$caminhoArquivoINI);
        }
    }

    public static function tearDownAfterClass() {
        parent::tearDownAfterClass();
        unlink(self::$caminhoArquivoINI);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function oCaminhoDoArquivoDeConfiguracaoPadraoFicaNaRaiz() {
        $this->assertStringEndsWith($this->caminhoPadraoArquivoINI, Config::getCaminhoArquivoConfig());
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     */
    public function seNaoExistirArquivoDeConfiguracaoNoPrimeiroAcessoDaClasseConfigEntaoUmDeveSerCriado() {
        Config::setCaminhoArquivoConfig(self::$caminhoArquivoINI);
        $this->configObj = Config::getInstance();

        $this->assertFileExists(self::$caminhoArquivoINI);
    }

    /**
     * @author Denys Xavier <equipe@tiexpert.net>
     * @test
     * @expectedException InvalidArgumentException
     */
    public function aoAlterarCaminhoDoArquivoDeConfiguracaoONovoArquivoDeveSerCarregado() {
        $caminhoArquivoConfigTemp = "./configTemp.ini";

        $this->configObj = Config::getInstance();

        try {
            $this->configObj->getOpcao("grupoTeste1", "chaveTeste1");
        } catch (InvalidArgumentException $e) {
            $handler = fopen($caminhoArquivoConfigTemp, "w");
            fwrite($handler, "[grupoTeste1]" . PHP_EOL . "chaveTeste1=1234567890");
            fclose($handler);

            Config::setCaminhoArquivoConfig($caminhoArquivoConfigTemp);

            $this->assertEquals("1234567890", $this->configObj->getOpcao("grupoTeste1", "chaveTeste1"));
            
            unlink($caminhoArquivoConfigTemp);
            Config::setCaminhoArquivoConfig(self::$caminhoArquivoINI);
            return;
        }

        $this->fail("Uma exceção deveria ser lançada para opções que não tem grupo nem chaves existentes.");
    }

}
