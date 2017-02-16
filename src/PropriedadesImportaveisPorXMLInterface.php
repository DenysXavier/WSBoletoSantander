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
 * Interface que define as ações necessárias para importar as propriedades de uma classe.
 * 
 * Idealmente, o XML importado deve ter a mesma estrutura de nós retornada pelo WS do banco.
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
interface PropriedadesImportaveisPorXMLInterface {

    /** Carrega as propriedades da instância da classe que implementa esta interface usando a estrutura XML
     * 
     * @param \DOMDocument $xml Estrutura XML legível
     */
    public function carregarPorXML(\DOMDocument $xml);
}
