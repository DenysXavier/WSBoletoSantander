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
 * Classe que representa um documento de boleto bancário.
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Boleto implements PropriedadesExportaveisParaArrayInterface, PropriedadesImportaveisPorXMLInterface {

    private $convenio;
    private $pagador;
    private $titulo;

    public function __construct(Convenio $convenio = NULL, Pagador $pagador = NULL, Titulo $titulo = NULL) {
        $this->convenio = $convenio;
        $this->pagador = $pagador;
        $this->titulo = $titulo;
    }

    public function getConvenio() {
        return $this->convenio;
    }

    public function getPagador() {
        return $this->pagador;
    }

    public function getTitulo() {
        return $this->titulo;
    }

    public function setConvenio(Convenio $convenio) {
        $this->convenio = $convenio;
        return $this;
    }

    public function setPagador(Pagador $pagador) {
        $this->pagador = $pagador;
        return $this;
    }

    public function setTitulo(Titulo $titulo) {
        $this->titulo = $titulo;
        return $this;
    }

    /** Exporta um array associativo no qual as chaves são as propriedades representadas como no WebService do Santander
     * 
     * @return array
     */
    public function exportarArray() {
        return array_merge($this->convenio->exportarArray(), $this->pagador->exportarArray(), $this->titulo->exportarArray(), $this->titulo->getInstrucoes()->exportarArray());
    }

    /** Carrega as propriedades da instância usando a estrutura XML
     * 
     * @param \DOMDocument $xml Estrutura XML legível
     */
    public function carregarPorXML(\DOMDocument $xml) {
        $convenio = new Convenio();
        $convenio->carregarPorXML($xml);
        $this->convenio = $convenio;

        $pagador = new Pagador();
        $pagador->carregarPorXML($xml);
        $this->pagador = $pagador;

        $titulo = new Titulo();
        $titulo->carregarPorXML($xml);
        $this->titulo = $titulo;
    }

}
