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

namespace TIExpert\WSBoletoSantander;

/** Classe que representa um ticket a ser usado em um serviço de boletos do Santander
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Ticket {

    private $nsu;
    private $data;
    private $ambiente;
    private $estacao;
    private $autenticacao;

    public function __construct() {
        $this->data = new \DateTime();
        $this->ambiente = Config::getInstance()->getGeral("ambiente");
        $this->estacao = Config::getInstance()->getGeral("estacao");
    }

    public function getNsu() {
        return $this->nsu;
    }

    public function getData() {
        return $this->data;
    }

    public function getAmbiente() {
        return $this->ambiente;
    }

    public function getEstacao() {
        return $this->estacao;
    }

    public function getAutenticacao() {
        return $this->autenticacao;
    }

    public function setNsu($nsu) {
        if ($this->ambiente == "T") {
            $nsu = "TST" . $nsu;
        }

        $this->nsu = $nsu;
        return $this;
    }

    public function setData(\DateTime $data) {
        try {
            $this->data = Util::converterParaDateTime($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function setEstacao($estacao) {
        $this->estacao = $estacao;
        return $this;
    }

    public function setAutenticacao($autenticacao) {
        $this->autenticacao = $autenticacao;
        return $this;
    }

    /** Exporta as propriedades da classe para um formato XML de acordo com a documentação do banco Santander
     * 
     * @param type $nomeNoRaiz Nome do nó raiz das propriedades
     * @return string
     */
    public function exportarParaXml($nomeNoRaiz = NULL) {
        $xml = new \XMLWriter();
        $xml->openMemory();

        if (!is_null($nomeNoRaiz)) {
            $xml->startElement($nomeNoRaiz);
        }

        $xml->writeElement("nsu", $this->nsu);
        $xml->writeElement("dtNsu", $this->data->format(Config::getInstance()->getGeral("formato_data")));
        $xml->writeElement("tpAmbiente", $this->ambiente);
        $xml->writeElement("estacao", $this->estacao);
        $xml->writeElement("ticket", $this->autenticacao);

        if (!is_null($nomeNoRaiz)) {
            $xml->endElement();
        }

        return $xml->outputMemory();
    }

}
