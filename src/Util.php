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

/** Classe utilitária de responsabilidades diversas
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Util {

    /** Tenta converter um parâmetro para um objeto \DateTime. Se o parâmetro for um objeto DateTime ou NULL, o próprio parâmetro é retornado.
     * 
     * @param mixed $param Parâmetro que será convertido
     * @return \DateTime
     * @throws \Exception
     * @throws \InvalidArgumentException
     */
    public static function converterParaDateTime($param) {
        try {
            if ($param == "now") {
                return new \DateTime();
            } else if (is_string($param)) {
                return self::converterParaDateTimeUmaString($param);
            } else if ($param instanceof \DateTime || is_null($param)) {
                return $param;
            }

            throw new \InvalidArgumentException("Não é possível converter parâmetro " . gettype($param) . " para uma data válida.");
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /** Tenta converter um parâmetro do tipo string para um objeto \DateTime.
     * 
     * @param string $param String que será convertida
     * @return \DateTime
     * @throws \InvalidArgumentException
     */
    private static function converterParaDateTimeUmaString($param) {
        $formato_data = Config::getInstance()->getGeral("formato_data");
        $data = \DateTime::createFromFormat($formato_data, $param);

        if (!$data) {
            throw new \InvalidArgumentException("Não é possível converter '" . $param . "' em uma data válida.");
        }

        return $data;
    }

    /** Formata um número em uma string específica baseada em sua precisão.
     * 
     * Este método diferencia-se da função number_format nativa do PHP por não arredondar o número.
     * 
     * @param float $numero Número a ser formatado
     * @param int $precisao Quantidade de casas decimais
     * @param string $separadorDecimal Caracter usado como separador decimal
     * @param string $separadorMilhar Caracter usado como separador de milhar
     */
    public static function formatarNumero($numero, $precisao = 0, $separadorDecimal = '.', $separadorMilhar = '') {
        $partes = explode('.', $numero);

        $numeroFormatado = self::agruparMilhares($partes[0], $separadorMilhar);

        if ($precisao > 0) {
            if (!isset($partes[1])) {
                $partes[1] = '0';
            }

            $parteFracionadaFormatada = str_pad($partes[1], $precisao, '0', STR_PAD_RIGHT);
            $numeroFormatado .= $separadorDecimal . substr($parteFracionadaFormatada, 0, $precisao);
        }

        return $numeroFormatado;
    }

    /** Formata um número inteiro como uma string separando grupos de 3 algarismos com algum caracter especificado
     * 
     * @param int $numero Número inteiro a ser formatado
     * @param string $caracterDeAgrupamento Caracter a ser usado para marcar o agrupamento dos número
     */
    private static function agruparMilhares($numero, $caracterDeAgrupamento = '') {
        $algarismosInvertidos = strrev($numero);
        $grupoAlgarismosInvertidos = str_split($algarismosInvertidos, 3);
        $numeroFormatadoInvertido = implode($caracterDeAgrupamento, $grupoAlgarismosInvertidos);

        return strrev($numeroFormatadoInvertido);
    }

}
