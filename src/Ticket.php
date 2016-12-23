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

    /** @property int $nsu Número seqüencial único por Convênio / Data */
    private $nsu;

    /** @property \DateTime $data Data do NSU gerado */
    private $data;

    /** @property string $ambiente Tipo de ambiente que está sendo usado. T = teste, P = produção */
    private $ambiente;

    /** @property string $estacao Código da Estação gerado pelo Banco Santander */
    private $estacao;

    /** @property string $autenticacao Código de autenticação retornado de uma solicitação ao serviço */
    private $autenticacao;

    /**
     * Cria uma nova instância de Ticket
     */
    public function __construct() {
        $this->data = new \DateTime();
        $this->ambiente = Config::getInstance()->getGeral("ambiente");
        $this->estacao = Config::getInstance()->getGeral("estacao");
    }

    /** Obtém o número seqüencial único por Convênio / Data
     * 
     * @return int
     */
    public function getNsu() {
        return $this->nsu;
    }

    /** Obtém a data do NSU gerado
     * 
     * @return \DateTime
     */
    public function getData() {
        return $this->data;
    }

    /** Obtém o Tipo de ambiente que está sendo usado
     * 
     * @return string
     */
    public function getAmbiente() {
        return $this->ambiente;
    }

    /** Obtém o código da Estação gerado pelo Banco Santander
     * 
     * @return string
     */
    public function getEstacao() {
        return $this->estacao;
    }

    /** Obtém o código de autenticação retornado de uma solicitação ao serviço
     * 
     * @return string
     */
    public function getAutenticacao() {
        return $this->autenticacao;
    }

    /** Determina o número seqüencial único por Convênio / Data
     * 
     * @param string $nsu Número seqüencial único por Convênio / Data
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
    public function setNsu($nsu) {
        if ($this->ambiente == "T") {
            $nsu = "TST" . $nsu;
        }

        $this->nsu = $nsu;
        return $this;
    }

    /** Determina a data do NSU gerado
     * 
     * @param \DateTime $data Data do NSU gerado
     * @throws \Exception
     */
    public function setData(\DateTime $data) {
        try {
            $this->data = Util::converterParaDateTime($data);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /** Determina o tipo de ambiente que está sendo usado
     * 
     * @param string $ambiente Tipo de ambiente que está sendo usado. T = teste, P = produção
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
    function setAmbiente($ambiente) {
        $this->ambiente = $ambiente;
        return $this;
    }

    /** Determina o código da Estação gerado pelo Banco Santander
     * 
     * @param string $estacao Código da Estação gerado pelo Banco Santander
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
    public function setEstacao($estacao) {
        $this->estacao = $estacao;
        return $this;
    }

    /** Determina o código de autenticação retornado de uma solicitação ao serviço
     * 
     * @param type $autenticacao Código de autenticação retornado de uma solicitação ao serviço
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
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
