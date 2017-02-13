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

/** Classe que determina as instruções do que o banco Santander deve fazer com o título bancário.
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class InstrucoesDeTitulo implements PropriedadesExportaveisParaArrayInterface, PropriedadesImportaveisPorXMLInterface {

    /** @property float $multa Percentual da multa com 2 decimais. Opcional. */
    private $multa;

    /** @property int $multarApos Quantidade de dias após o vencimento do título para incidência da multa. Opcional. */
    private $multarApos;

    /** @property float $juros Percentual de juros com 2 decimais. Opcional. */
    private $juros;

    /** @property int $tipoDesconto Tipo de desconto a ser aplicado. Sendo: 0 = isento; 1 = Valor fixo até a data informada; 2 = Percentual até a data informada; 3 = Valor por antecipação dia corrido. */
    private $tipoDesconto;

    /** @property float $valorDesconto Valor ou percentual de desconto, com 2 casas decimais. */
    private $valorDesconto;

    /** @property \DateTime $dataLimiteDesconto Data limite para Desconto. */
    private $dataLimiteDesconto;

    /** @property float $valorAbatimento Valor do abatimento. Opcional. */
    private $valorAbatimento;

    /** @property int $tipoProtesto Tipo de protesto a ser adotado. Sendo: 0 = Nao Protestar; 1 = Protestar dias corridos; 2 = Protestar dias úteis; 3 = Utilizar Perfil Cedente. */
    private $tipoProtesto;

    /** @property int $protestarApos Quantidade de dias após o vencimento para protesto. */
    private $protestarApos;

    /** @property int $baixarApos Quantidade de dias após o vencimento para baixa/devolução do título. */
    private $baixarApos;

    public function __construct($multa = NULL, $multarApos = NULL, $juros = NULL, $tipoDesconto = NULL, $valorDesconto = NULL, $dataLimiteDesconto = NULL, $valorAbatimento = NULL, $tipoProtesto = NULL, $protestarApos = NULL, $baixarApos = NULL) {
        $this->setMulta($multa);
        $this->setMultarApos($multarApos);
        $this->setJuros($juros);
        $this->setTipoDesconto($tipoDesconto);
        $this->setValorDesconto($valorDesconto);
        $this->setDataLimiteDesconto($dataLimiteDesconto);
        $this->setValorAbatimento($valorAbatimento);
        $this->setTipoProtesto($tipoProtesto);
        $this->setProtestarApos($protestarApos);
        $this->setBaixarApos($baixarApos);
    }

    /** Obtém o percentual da multa, com 2 decimais.
     * 
     * @return float
     */
    public function getMulta() {
        return $this->multa;
    }

    /** Obtém a quantidade de dias após o vencimento do título para incidência da multa.
     * 
     * @return int
     */
    public function getMultarApos() {
        return $this->multarApos;
    }

    /** Obtém o percentual de juros com 2 decimais.
     * 
     * @return float
     */
    public function getJuros() {
        return $this->juros;
    }

    /** Obtém o tipo de desconto a ser aplicado.
     * 
     * @return int
     */
    public function getTipoDesconto() {
        return $this->tipoDesconto;
    }

    /** Obtém o valor ou percentual de desconto com 2 casas decimais.
     * 
     * @return type
     */
    public function getValorDesconto() {
        return $this->valorDesconto;
    }

    /** Obtém a data limite para Desconto.
     * 
     * @return \DateTime
     */
    public function getDataLimiteDesconto() {
        return $this->dataLimiteDesconto;
    }

    /** Obtém o valor do abatimento.
     * 
     * @return float
     */
    public function getValorAbatimento() {
        return $this->valorAbatimento;
    }

    /** Obtém o tipo de protesto a ser adotado.
     * 
     * @return int
     */
    public function getTipoProtesto() {
        return $this->tipoProtesto;
    }

    /** Obtém a quantidade de dias após o vencimento para protesto.
     * 
     * @return int
     */
    public function getProtestarApos() {
        return $this->protestarApos;
    }

    /** Obtém a quantidade de dias após o vencimento para baixa/devolução do título.
     * 
     * @return int
     */
    public function getBaixarApos() {
        return $this->baixarApos;
    }

    /** Determina o percentual da multa, com 2 decimais.
     * 
     * @param float $multa Percentual da multa, com 2 decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setMulta($multa) {
        $this->multa = $multa;
        return $this;
    }

    /** Determina a quantidade de dias após o vencimento do título para incidência da multa.
     * 
     * @param int $multarApos Quantidade de dias após o vencimento do título para incidência da multa.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setMultarApos($multarApos) {
        $this->multarApos = $multarApos;
        return $this;
    }

    /** Determina o percentual de juros, com 2 decimais.
     * 
     * @param float $juros Percentual de juros com 2 decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setJuros($juros) {
        $this->juros = $juros;
        return $this;
    }

    /** Determina o tipo de desconto a ser aplicado.
     * 
     * @param int $tipoDesconto Tipo de desconto a ser aplicado. Sendo: 0 = isento; 1 = Valor fixo até a data informada; 2 = Percentual até a data informada; 3 = Valor por antecipação dia corrido.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setTipoDesconto($tipoDesconto) {
        if (is_null($tipoDesconto)) {
            $tipoDesconto = Config::getInstance()->getInstrucao("tipo_desconto");
        }

        $this->tipoDesconto = $tipoDesconto;
        return $this;
    }

    /** Determina o valor ou percentual de desconto com 2 casas decimais.
     * 
     * @param float $valorDesconto Valor ou percentual de desconto com 2 casas decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setValorDesconto($valorDesconto) {
        if (is_null($valorDesconto)) {
            $valorDesconto = Config::getInstance()->getInstrucao("valor_desconto");
        }

        $this->valorDesconto = $valorDesconto;
        return $this;
    }

    /** Determina a data limite para Desconto.
     * 
     * @param \DateTime $dataLimiteDesconto Data limite para Desconto.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setDataLimiteDesconto($dataLimiteDesconto) {
        if (is_null($dataLimiteDesconto)) {
            $dataLimiteDesconto = Config::getInstance()->getInstrucao("data_limite_desconto");
        }

        try {
            $this->dataLimiteDesconto = Util::converterParaDateTime($dataLimiteDesconto);
        } catch (\Exception $ex) {
            throw $ex;
        }
        return $this;
    }

    /** Determina o valor do abatimento.
     * 
     * @param float $valorAbatimento Valor do abatimento.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setValorAbatimento($valorAbatimento) {
        $this->valorAbatimento = $valorAbatimento;
        return $this;
    }

    /** Determina o tipo de protesto a ser adotado.
     * 
     * @param int $tipoProtesto Tipo de protesto a ser adotado. Sendo: 0 = Nao Protestar; 1 = Protestar dias corridos; 2 = Protestar dias úteis; 3 = Utilizar Perfil Cedente.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setTipoProtesto($tipoProtesto) {
        if (is_null($tipoProtesto)) {
            $tipoProtesto = Config::getInstance()->getInstrucao("tipo_protesto");
        }

        $this->tipoProtesto = $tipoProtesto;
        return $this;
    }

    /** Determina a quantidade de dias após o vencimento para protesto.
     * 
     * @param int $protestarApos Quantidade de dias após o vencimento para protesto.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setProtestarApos($protestarApos) {
        $this->protestarApos = $protestarApos;
        return $this;
    }

    /** Determina a quantidade de dias após o vencimento para baixa/devolução do título.
     * 
     * @param int $baixarApos Quantidade de dias após o vencimento para baixa/devolução do título.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    public function setBaixarApos($baixarApos) {
        if (is_null($baixarApos)) {
            $baixarApos = Config::getInstance()->getInstrucao("baixar_apos");
        }

        $this->baixarApos = $baixarApos;
        return $this;
    }

    /** Exporta um array associativo no qual as chaves são as propriedades representadas como no WebService do Santander
     * 
     * @return array
     */
    public function exportarArray() {
        $formatoDataPadrao = Config::getInstance()->getGeral("formato_data");

        $array["TITULO.PC-MULTA"] = $this->getMulta();
        $array["TITULO.QT-DIAS-MULTA"] = $this->getMultarApos();
        $array["TITULO.PC-JURO"] = $this->getJuros();
        $array["TITULO.TP-DESC"] = $this->getTipoDesconto();
        $array["TITULO.VL-DESC"] = $this->getValorDesconto();
        $array["TITULO.DT-LIMI-DESC"] = $this->getDataLimiteDesconto()->format($formatoDataPadrao);
        $array["TITULO.VL-ABATIMENTO"] = $this->getValorAbatimento();
        $array["TITULO.TP-PROTESTO"] = $this->getTipoProtesto();
        $array["TITULO.QT-DIAS-PROTESTO"] = $this->getProtestarApos();
        $array["TITULO.QT-DIAS-BAIXA"] = $this->getBaixarApos();

        return $array;
    }

    /** Carrega as propriedades da instância usando a estrutura XML
     * 
     * @param \DOMDocument $xml Estrutura XML legível
     */
    public function carregarPorXML(\DOMDocument $xml) {
        $leitor = new LeitorSimplesXML($xml);

        $this->setBaixarApos($leitor->getValorNo("qtDiasBaixa"));
        $this->setDataLimiteDesconto($leitor->getValorNo("dtLimiDesc"));
        $this->setJuros($leitor->getValorNo("pcJuro"));
        $this->setMulta($leitor->getValorNo("pcMulta"));
        $this->setMultarApos($leitor->getValorNo("qtDiasMulta"));
        $this->setProtestarApos($leitor->getValorNo("qtDiasProtesto"));
        $this->setTipoDesconto($leitor->getValorNo("tpDesc"));
        $this->setTipoProtesto($leitor->getValorNo("tpProtesto"));
        $this->setValorAbatimento($leitor->getValorNo("vlAbatimento") / 100);
        $this->setValorDesconto($leitor->getValorNo("vlDesc") / 100);
    }

}
