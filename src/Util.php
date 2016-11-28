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
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Util {

    public static function converterParaDateTime($param) {
        try {
            if (is_string($param)) {
                return new \DateTime($param);
            } else if ($param instanceof \DateTime) {
                return $param;
            }

            throw new \InvalidArgumentException("Não é possível converter parâmetro " . gettype($param) . " para uma data válida.");
        } catch (Exception $ex) {
            throw $ex;
        }
    }

}
