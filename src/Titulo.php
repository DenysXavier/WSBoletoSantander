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

class Titulo {

    /** @property string $nossoNumero Número do Título no Banco. */
    private $nossoNumero;

    /** @property string $seuNumero Número do Título no cliente. Opcional. */
    private $seuNumero;

    /** @property \DateTime $dataVencimento Data de vencimento do título. */
    private $dataVencimento;

    /** @property \DateTime $dataEmissao Data de emissão do Título. */
    private $dataEmissao;

    /** @property int $especie Código da Espécie do Documento. */
    private $especie;

    /** @property float $valor Valor nominal do título, com 2 casas decimais. */
    private $valor;

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

    /** @property string $mensagem Mensagem do boleto. */
    private $mensagem;

    /** Cria uma nova instância de Titulo
     * 
     * @param float $valor Valor nominal do título com 2 casas decimais. 
     * @param string $nossoNumero Número do Título no Banco.
     * @param string $seuNumero Número do Título no cliente.
     * @param \DateTime $dataVencimento Data de vencimento do título.
     * @param string $mensagem Mensagem do boleto.
     * @param \DateTime $dataEmissao Data de emissão do Título.
     * @param int $especie Código da Espécie do Documento.     
     * @param float $multa Percentual da multa com 2 decimais.
     * @param int $multarApos Quantidade de dias após o vencimento do título para incidência da multa.
     * @param float $juros Percentual de juros com 2 decimais.
     * @param int $tipoDesconto Tipo de desconto a ser aplicado.
     * @param float $valorDesconto Valor ou percentual de desconto, com 2 casas decimais.
     * @param \DateTime $dataLimiteDesconto Data limite para Desconto.
     * @param float $valorAbatimento Valor do abatimento.
     * @param int $tipoProtesto Tipo de protesto a ser adotado.
     * @param int $protestarApos Quantidade de dias após o vencimento para protesto.
     * @param int $baixarApos Quantidade de dias após o vencimento para baixa/devolução do título.   
     */
    public function __construct($valor, $nossoNumero, $seuNumero, $dataVencimento, $mensagem, $dataEmissao, $especie, $multa, $multarApos, $juros, $tipoDesconto, $valorDesconto, $dataLimiteDesconto, $valorAbatimento, $tipoProtesto, $protestarApos, $baixarApos) {
        if (is_null($dataVencimento)) {
            $dataVencimento = new \DateTime();
        }

        if (is_null($dataEmissao)) {
            $dataEmissao = new \DateTime();
        }

        $this->setNossoNumero($nossoNumero);
        $this->setSeuNumero($seuNumero);
        $this->setDataVencimento($dataVencimento);
        $this->setMensagem($mensagem);
        $this->setDataEmissao($dataEmissao);
        $this->setEspecie($especie);
        $this->setValor($valor);
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

    /** Obtém o número do título no banco.
     * 
     * @return string
     */
    function getNossoNumero() {
        return $this->nossoNumero;
    }

    /** Obtém o número do Título no cliente.
     * 
     * @return string
     */
    function getSeuNumero() {
        return $this->seuNumero;
    }

    /** Obtém a data de vencimento do título.
     * 
     * @return \DateTime
     */
    function getDataVencimento() {
        return $this->dataVencimento;
    }

    /** Obtém a data de emissão do Título.
     * 
     * @return \DateTime
     */
    function getDataEmissao() {
        return $this->dataEmissao;
    }

    /** Obtém o código da Espécie do Documento.
     * 
     * @return int
     */
    function getEspecie() {
        return $this->especie;
    }

    /** Obtém o valor nominal do título, com 2 casas decimais.
     * 
     * @return float
     */
    function getValor() {
        return $this->valor;
    }

    /** Obtém o percentual da multa, com 2 decimais.
     * 
     * @return float
     */
    function getMulta() {
        return $this->multa;
    }

    /** Obtém a quantidade de dias após o vencimento do título para incidência da multa.
     * 
     * @return int
     */
    function getMultarApos() {
        return $this->multarApos;
    }

    /** Obtém o percentual de juros com 2 decimais.
     * 
     * @return float
     */
    function getJuros() {
        return $this->juros;
    }

    /** Obtém o tipo de desconto a ser aplicado.
     * 
     * @return int
     */
    function getTipoDesconto() {
        return $this->tipoDesconto;
    }

    /** Obtém o valor ou percentual de desconto com 2 casas decimais.
     * 
     * @return type
     */
    function getValorDesconto() {
        return $this->valorDesconto;
    }

    /** Obtém a data limite para Desconto.
     * 
     * @return \DateTime
     */
    function getDataLimiteDesconto() {
        return $this->dataLimiteDesconto;
    }

    /** Obtém o valor do abatimento.
     * 
     * @return float
     */
    function getValorAbatimento() {
        return $this->valorAbatimento;
    }

    /** Obtém o tipo de protesto a ser adotado.
     * 
     * @return int
     */
    function getTipoProtesto() {
        return $this->tipoProtesto;
    }

    /** Obtém a quantidade de dias após o vencimento para protesto.
     * 
     * @return int
     */
    function getProtestarApos() {
        return $this->protestarApos;
    }

    /** Obtém a quantidade de dias após o vencimento para baixa/devolução do título.
     * 
     * @return int
     */
    function getBaixarApos() {
        return $this->baixarApos;
    }

    /** Obtém a mensagem do boleto.
     * 
     * @return string
     */
    function getMensagem() {
        return $this->mensagem;
    }

    /** Determina o número do título no banco.
     * 
     * @param string $nossoNumero Número do Título no Banco.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setNossoNumero($nossoNumero) {
        $this->nossoNumero = $nossoNumero;
        return $this;
    }

    /** Determina o número do Título no cliente.
     * 
     * @param string $seuNumero Número do Título no cliente.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setSeuNumero($seuNumero) {
        $this->seuNumero = $seuNumero;
        return $this;
    }

    /** Determina a data de vencimento do título.
     * 
     * @param \DateTime $dataVencimento Data de vencimento do título.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setDataVencimento($dataVencimento) {
        try {
            $this->dataVencimento = Util::converterParaDateTime($dataVencimento);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $this;
    }

    /** Determina a data de emissão do Título.
     * 
     * @param \DateTime $dataEmissao Data de emissão do Título.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setDataEmissao($dataEmissao) {
        try {
            $this->dataEmissao = Util::converterParaDateTime($dataEmissao);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $this;
    }

    /** Determina o código da Espécie do Documento.
     * 
     * @param int $especie Código da Espécie do Documento.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setEspecie($especie) {
        $this->especie = $especie;
        return $this;
    }

    /** Determina o valor nominal do título, com 2 casas decimais.
     * 
     * @param float $valor Valor nominal do título, com 2 casas decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setValor($valor) {
        $this->valor = $valor;
        return $this;
    }

    /** Determina o percentual da multa, com 2 decimais.
     * 
     * @param float $multa Percentual da multa, com 2 decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setMulta($multa) {
        $this->multa = $multa;
        return $this;
    }

    /** Determina a quantidade de dias após o vencimento do título para incidência da multa.
     * 
     * @param int $multarApos Quantidade de dias após o vencimento do título para incidência da multa.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setMultarApos($multarApos) {
        $this->multarApos = $multarApos;
        return $this;
    }

    /** Determina o percentual de juros, com 2 decimais.
     * 
     * @param float $juros Percentual de juros com 2 decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setJuros($juros) {
        $this->juros = $juros;
        return $this;
    }

    /** Determina o tipo de desconto a ser aplicado.
     * 
     * @param int $tipoDesconto Tipo de desconto a ser aplicado. Sendo: 0 = isento; 1 = Valor fixo até a data informada; 2 = Percentual até a data informada; 3 = Valor por antecipação dia corrido.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setTipoDesconto($tipoDesconto) {
        $this->tipoDesconto = $tipoDesconto;
        return $this;
    }

    /** Determina o valor ou percentual de desconto com 2 casas decimais.
     * 
     * @param float $valorDesconto Valor ou percentual de desconto com 2 casas decimais.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setValorDesconto($valorDesconto) {
        $this->valorDesconto = $valorDesconto;
        return $this;
    }

    /** Determina a data limite para Desconto.
     * 
     * @param \DateTime $dataLimiteDesconto Data limite para Desconto.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setDataLimiteDesconto($dataLimiteDesconto) {
        try {
            $this->dataLimiteDesconto = Util::converterParaDateTime($dataLimiteDesconto);
        } catch (Exception $ex) {
            throw $ex;
        }
        return $this;
    }

    /** Determina o valor do abatimento.
     * 
     * @param float $valorAbatimento Valor do abatimento.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setValorAbatimento($valorAbatimento) {
        $this->valorAbatimento = $valorAbatimento;
        return $this;
    }

    /** Determina o tipo de protesto a ser adotado.
     * 
     * @param int $tipoProtesto Tipo de protesto a ser adotado. Sendo: 0 = Nao Protestar; 1 = Protestar dias corridos; 2 = Protestar dias úteis; 3 = Utilizar Perfil Cedente.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setTipoProtesto($tipoProtesto) {
        $this->tipoProtesto = $tipoProtesto;
        return $this;
    }

    /** Determina a quantidade de dias após o vencimento para protesto.
     * 
     * @param int $protestarApos Quantidade de dias após o vencimento para protesto.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setProtestarApos($protestarApos) {
        $this->protestarApos = $protestarApos;
        return $this;
    }

    /** Determina a quantidade de dias após o vencimento para baixa/devolução do título.
     * 
     * @param int $baixarApos Quantidade de dias após o vencimento para baixa/devolução do título.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setBaixarApos($baixarApos) {
        $this->baixarApos = $baixarApos;
        return $this;
    }

    /** Determina a mensagem do boleto.
     * 
     * @param string $mensagem Mensagem do boleto.
     * @return \TIExpert\WSBoletoSantander\Titulo
     */
    function setMensagem($mensagem) {
        $this->mensagem = $mensagem;
        return $this;
    }

}
