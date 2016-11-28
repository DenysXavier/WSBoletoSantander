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

/**
 * Classe que representa os dados do pagador para utilização do WS Santander
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Pagador implements PropriedadesExportaveisParaArrayInterface {

    /** property int $tipoDoc Tipo de Documento do Pagador */
    private $tipoDoc;

    /** property string $numeroDoc CPF/CNPJ do Pagador */
    private $numeroDoc;

    /** property string $nome Nome do Pagador */
    private $nome;

    /** property string $endereco Endereço do Pagador */
    private $endereco;

    /** property string $bairro Bairro do Pagador */
    private $bairro;

    /** property string $cidade Cidade do Pagador */
    private $cidade;

    /** property string $UF UF do Pagador */
    private $UF;

    /** property string $CEP CEP do Pagador */
    private $CEP;

    /** Cria uma nova instância de Pagador
     * 
     * @param int $tipoDoc Tipo de Documento do Pagador
     * @param string $numeroDoc CPF/CNPJ do Pagador
     * @param string $nome Nome do Pagador
     * @param string $endereco Endereço do Pagador
     * @param string $bairro Bairro do Pagador
     * @param string $cidade Cidade do Pagador
     * @param string $UF UF do Pagador
     * @param string $CEP CEP do Pagador
     */
    public function __construct($tipoDoc = NULL, $numeroDoc = NULL, $nome = NULL, $endereco = NULL, $bairro = NULL, $cidade = NULL, $UF = NULL, $CEP = NULL) {
        $this->tipoDoc = $tipoDoc;
        $this->numeroDoc = $numeroDoc;
        $this->nome = $nome;
        $this->endereco = $endereco;
        $this->bairro = $bairro;
        $this->cidade = $cidade;
        $this->UF = $UF;
        $this->CEP = $CEP;
    }

    /** Obtém o tipo de documento do Pagador
     * 
     * @return string
     */
    public function getTipoDoc() {
        return $this->tipoDoc;
    }

    /** Obtém o CPF/CNPJ do Pagador
     * 
     * @return string
     */
    public function getNumeroDoc() {
        return $this->numeroDoc;
    }

    /** Obtém o nome do Pagador
     * 
     * @return string
     */
    public function getNome() {
        return $this->nome;
    }

    /** Obtém o endereço do Pagador
     * 
     * @return string
     */
    public function getEndereco() {
        return $this->endereco;
    }

    /** Obtém o bairro do Pagador
     * 
     * @return string
     */
    public function getBairro() {
        return $this->bairro;
    }

    /** Obtém a cidade do Pagador
     * 
     * @return string
     */
    public function getCidade() {
        return $this->cidade;
    }

    /** Obtém a UF do Pagador
     * 
     * @return string
     */
    public function getUF() {
        return $this->UF;
    }

    /** Obtém o CEP do Pagador
     * 
     * @return string
     */
    public function getCEP() {
        return $this->CEP;
    }

    /** Determina o tipo de documento do Pagador
     * 
     * @param string $tipoDoc Tipo de Documento do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setTipoDoc($tipoDoc) {
        $this->tipoDoc = $tipoDoc;
        return $this;
    }

    /** Determina o CPF/CNPJ do Pagador
     * 
     * @param string $numeroDoc CPF/CNPJ do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setNumeroDoc($numeroDoc) {
        $this->numeroDoc = $numeroDoc;
        return $this;
    }

    /** Determina o nome do Pagador
     * 
     * @param string $nome Nome do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setNome($nome) {
        $this->nome = $nome;
        return $this;
    }

    /** Determina o endereço do Pagador
     * 
     * @param string $endereco Endereço do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setEndereco($endereco) {
        $this->endereco = $endereco;
        return $this;
    }

    /** Determina o bairro do Pagador
     * 
     * @param string $bairro Bairro do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setBairro($bairro) {
        $this->bairro = $bairro;
        return $this;
    }

    /** Determina a Cidade do Pagador
     * 
     * @param string $cidade Cidade do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setCidade($cidade) {
        $this->cidade = $cidade;
        return $this;
    }

    /** Determina a UF do Pagador
     * 
     * @param string $UF UF do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setUF($UF) {
        $this->UF = $UF;
        return $this;
    }

    /** Determina o CEP do Pagador
     * 
     * @param string $CEP CEP do Pagador
     * @return \TIExpert\WSBoletoSantander\Pagador
     */
    public function setCEP($CEP) {
        $this->CEP = $CEP;
        return $this;
    }

    /** Exporta um array associativo no qual as chaves são as propriedades representadas como no WebService do Santander
     * 
     * @return array
     */
    public function exportarArray() {
        $array["PAGADOR.TP-DOC"] = $this->tipoDoc;
        $array["PAGADOR.NUM-DOC"] = $this->numeroDoc;
        $array["PAGADOR.NOME"] = $this->nome;
        $array["PAGADOR.ENDER"] = $this->endereco;
        $array["PAGADOR.BAIRRO"] = $this->bairro;
        $array["PAGADOR.CIDADE"] = $this->cidade;
        $array["PAGADOR.UF"] = $this->UF;
        $array["PAGADOR.CEP"] = $this->CEP;
        return $array;
    }

}
