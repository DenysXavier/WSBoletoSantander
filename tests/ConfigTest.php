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

class ConfigTest extends \PHPUnit_Framework_TestCase {

    private $caminhoArquivoINI = "./config.ini";
    private $configObj;

    protected function setUp() {
        parent::setUp();
        if (file_exists($this->caminhoArquivoINI)) {
            unlink($this->caminhoArquivoINI);
        }
    }

    protected function tearDown() {
        parent::tearDown();
        unlink($this->caminhoArquivoINI);
    }

    /** @test */
    public function seNaoExistirArquivoDeConfiguracaoNoPrimeiroAcessoDaClasseConfigEntaoUmDeveSerCriado() {
        Config::setCaminhoArquivoConfig($this->caminhoArquivoINI);
        $this->configObj = Config::getInstance();

        $this->assertFileExists($this->caminhoArquivoINI);
    }

}
