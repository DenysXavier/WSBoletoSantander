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
 * Classe que possibilita a integração entre o PHP e o sistema de registro de boletos online do banco Santander
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class BoletoSantanderServico {

    const TICKET_ENDPOINT = "https://ymbdlb.santander.com.br/dl-ticket-services/TicketEndpointService";
    const COBRANCA_ENDPOINT = "https://ymbcash.santander.com.br/ymbsrv/CobrancaEndpointService";

    private $comunicador;

    /**
     * Cria uma nova instância de BoletoSantanderServico
     */
    public function __construct() {
        $this->comunicador = new ComunicadorCurlSOAP();
    }

    public function solicitarTicketInclusao(Boleto $boleto) {
        $xml = $this->iniciarXmlSoapEnvelope();

        $xml->startElementNs("impl", "create", "http://impl.webservice.dl.app.bsbr.altec.com/");
        $xml->startElement("TicketRequest");
        $xml->startElement("dados");

        $this->anexarArrayMapeado($xml, $boleto);

        $xml->endElement();
        $xml->writeElement("expiracao", 100);
        $xml->writeElement("sistema", "YMB");
        $xml->endDocument();

        $retorno = $this->comunicador->chamar(self::TICKET_ENDPOINT, $xml->outputMemory());

        return $this->processarRetornoParaTicket($retorno);
    }

    /** Inicia um novo objeto XMLWriter com o nó raiz Envelope, o nó Header e o nó Body aberto para receber conteúdo.
     * 
     * @return \XMLWriter
     */
    private function iniciarXmlSoapEnvelope() {
        $xml = new \XMLWriter();
        $xml->openMemory();

        $xml->startElementNs("soapenv", "Envelope", "http://schemas.xmlsoap.org/soap/envelope/");
        $xml->writeElementNs("soapenv", "Header", NULL);
        $xml->startElementNs("soapenv", "Body", NULL);

        return $xml;
    }

    /** Anexa um array associativo das propriedades exportáveis de uma classe no atual nó aberto do XMLWriter informado
     * 
     * @param \XMLWriter $xml Objeto XMLWriter com o nó aberto onde será anexado o array
     * @param \TIExpert\WSBoletoSantander\PropriedadesExportaveisParaArrayInterface $objeto Instância que terá suas propriedades anexadas ao XML como um array
     */
    private function anexarArrayMapeado(\XMLWriter $xml, PropriedadesExportaveisParaArrayInterface $objeto) {
        foreach ($objeto->exportarArray() as $key => $value) {
            $xml->startElement("entry");
            $xml->writeElement("key", $key);
            $xml->writeElement("value", $value);
            $xml->endElement();
        }
    }

    /** Processa a resposta de uma chamada a um serviço de solicitação de tíquete de segurança
     * 
     * @param string $response Resposta de uma chamada a um serviço de solicitação de tíquete de segurança
     * @return \TIExpert\WSBoletoSantander\Ticket
     * @throws \Exception
     */
    private function processarRetornoParaTicket($response) {
        if ($this->comunicador->ehSOAPFaultComoString($response)) {
            throw $this->comunicador->converterSOAPFaultStringParaException($response);
        }

        $XML = \DOMDocument::loadXML($response);

        try {
            $ticket = $this->criarTiqueteAPartirDaResposta($XML);
            return $ticket;
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    /** Cria um tíquete a partir de um XML de resposta de uma chamada a um serviço de solicitação de tíquete de segurança
     * 
     * @param \DOMDocument $dom XML de resposta de uma chamada a um serviço de solicitação de tíquete de segurança
     * @return \TIExpert\WSBoletoSantander\Ticket
     * @throws \Exception
     */
    private function criarTiqueteAPartirDaResposta(\DOMDocument $dom) {
        $retCode = $this->getNodeValue($dom, "retCode");

        if ($retCode === "0") {
            $ticket = new Ticket();
            $ticket->setAutenticacao($this->getNodeValue($dom, "ticket"));
            return $ticket;
        }

        throw new \Exception("O serviço de inclusão de título do Santander retornou um erro. Código: " . $retCode);
    }

    /** Obtém o valor do primeiro nó com o nome informado
     * 
     * @param \DOMDocument $doc Documento XML a ser pesquisado
     * @param type $tagName Nome do nó a ser pesquisado
     * @return string
     * @throws \Exception
     */
    private function getNodeValue(\DOMDocument $doc, $tagName) {
        try {
            return $doc->getElementsByTagName($tagName)->item(0)->nodeValue;
        } catch (\Exception $e) {
            throw $e;
        }
    }

}
