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

}
