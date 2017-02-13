<?php

/*
 * Copyright 2017 Denys.
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
 * Classe que auxilia na leitura de uma estrutura de um documento XML
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class LeitorSimplesXML {

    /** property \DOMDocument $xml Referência ao objeto contendo toda a estrutura XML a ser lida. */
    private $xml;

    /** Cria uma nova instância de LeitorSimplesXML
     * 
     * @param \DOMDocument $xml Objeto contendo toda estrutura XML a ser lida
     */
    public function __construct(\DOMDocument $xml) {
        $this->xml = $xml;
    }

    /** Obtém o valor do primeiro nó com o nome informado
     * 
     * @param string $tagName Nome do nó a ser pesquisado
     * @return string
     * @throws \Exception
     */
    public function getValorNo($tagName) {
        try {
            return $this->xml->getElementsByTagName($tagName)->item(0)->nodeValue;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
