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

use TIExpert\WSBoletoSantander\Config;

/**
 * Classe que representa o convênio usado na confecção do boleto
 *
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Convenio {

    private $codigoBanco;
    private $codigoConvenio;

    public function __construct($codigoBanco = NULL, $codigoConvenio = NULL) {
        $this->codigoBanco = $codigoBanco;
        $this->codigoConvenio = $codigoConvenio;

        if (is_null($codigoBanco) && is_null($codigoConvenio)) {
            $config = Config::getInstance();
            $this->codigoBanco = $config->getConvenio("banco_padrao");
            $this->codigoConvenio = $config->getConvenio("convenio_padrao");
        }
    }

    public function getCodigoBanco() {
        return $this->codigoBanco;
    }

    public function getCodigoConvenio() {
        return $this->codigoConvenio;
    }

    public function setCodigoBanco($codigoBanco) {
        $this->codigoBanco = $codigoBanco;
        return $this;
    }

    public function setCodigoConvenio($codigoConvenio) {
        $this->codigoConvenio = $codigoConvenio;
        return $this;
    }

}
