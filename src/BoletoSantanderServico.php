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
    const COBRANCA_ENDPOINT = "https://ymbcash.santander.com.br/ymbsrv/CobrancaV2EndpointService";

    /** @property \TIExpert\WSBoletoSantander\ComunicadorCurlSOAP $comunicador Referência ao objeto a ser usado como ponte de comunicação entre o serviço e a extensão cURL do PHP. */
    private $comunicador;

    /** Cria uma nova instância de BoletoSantanderServico
     * 
     * @param \TIExpert\WSBoletoSantander\ComunicadorCurlSOAP $comunicadorCurlSOAP Referência ao objeto a ser usado como ponte de comunicação entre o serviço e a extensão cURL do PHP.
     */
    public function __construct(ComunicadorCurlSOAP $comunicadorCurlSOAP) {
        $this->comunicador = $comunicadorCurlSOAP;
    }

    /** Solicita um tíquete de segurança para inclusão do boleto no Santander
     * 
     * @param \TIExpert\WSBoletoSantander\Boleto $boleto Boleto que deverá ser validado e pré-cadastrado no Santander
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
    public function solicitarTicketInclusao(Boleto $boleto) {
        return $this->gerarTicket($boleto);
    }

    /** Solicita um tíquete de segurança para sondagem de um boleto no Santander
     * 
     * @param \TIExpert\WSBoletoSantander\Convenio $convenio
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
    public function solicitarTicketSondagem(Convenio $convenio) {
        return $this->gerarTicket($convenio);
    }

    /** Faz chamada a um serviço gerador de tíquete baseado no objeto informado
     * 
     * @param \TIExpert\WSBoletoSantander\PropriedadesExportaveisParaArrayInterface $objeto Objeto passível de ter suas propriedades exportadas.
     * @return \TIExpert\WSBoletoSantander\Ticket
     */
    private function gerarTicket(PropriedadesExportaveisParaArrayInterface $objeto) {
        $xml = $this->iniciarXmlSoapEnvelope();

        $xml->startElementNs("impl", "create", "http://impl.webservice.dl.app.bsbr.altec.com/");
        $xml->startElement("TicketRequest");

        $xml->startElement("dados");
        $this->anexarArrayMapeado($xml, $objeto);
        $xml->endElement();

        $xml->writeElement("expiracao", 100);
        $xml->writeElement("sistema", "YMB");
        $xml->endDocument();

        $retorno = $this->executarServico(self::TICKET_ENDPOINT, $xml);

        $retornoDocumentoXML = $this->converterRespostaParaDOMDocument($retorno);
        return $this->processarRetornoParaTicket($retornoDocumentoXML);
    }

    /** Inclui um título a partir de um tíquete de segurança de inclusão
     * 
     * @param \TIExpert\WSBoletoSantander\Ticket $ticket Tíquete de segurança de inclusão.
     * @return boolean
     */
    public function incluirTitulo(Ticket $ticket) {
        $respostaXML = $this->procederTicket($ticket, "registraTitulo");

        return $this->tituloFoiIncluidoComSucesso($respostaXML);
    }

    /** Sonda um título a partir de um tíquete de segurança de sondagem
     * 
     * @param \TIExpert\WSBoletoSantander\Ticket $ticket Tíquete de segurança de sondagem.
     * @return \TIExpert\WSBoletoSantander\Boleto
     */
    public function sondarTitulo(Ticket $ticket) {
        $respostaXML = $this->procederTicket($ticket, "consultaTitulo");
        return $this->converterRespostaParaBoleto($respostaXML);
    }

    /** Encaminha um tíquete de segurança para endpoint do serviço do Santander
     * 
     * @param \TIExpert\WSBoletoSantander\Ticket $ticket Tíquete a ser enviado.
     * @param string $acao Nome do método a ser executado no endpoint
     * @return \DOMDocument
     */
    private function procederTicket(Ticket $ticket, $acao) {
        $xml = $this->criarEnvelopeParaTicket($ticket, $acao);
        $resposta = $this->executarServico(self::COBRANCA_ENDPOINT, $xml);
        return $this->converterRespostaParaDOMDocument($resposta);
    }

    /** Cria todo o XML do envelope SOAP contendo as informações do tíquete
     * 
     * @param \TIExpert\WSBoletoSantander\Ticket $ticket Tíquete a ser envelopado
     * @param string $nomeAcao Nome do nó que será o método a ser chamado no endpoint
     * @return \XMLWriter
     */
    private function criarEnvelopeParaTicket(Ticket $ticket, $nomeAcao) {
        $xml = $this->iniciarXmlSoapEnvelope();
        $xml->startElement($nomeAcao);
        $xml->writeRaw($ticket->exportarParaXml("dto"));
        $xml->endDocument();
        return $xml;
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

    /** Executa o serviço usando o comunicador
     * 
     * @param string $url URL do endpoint onde está localizado o serviço
     * @param \XMLWriter $xmlObject Objeto usado na escrita do XML que deve ser transmitido para o endpoint
     * @return string
     */
    private function executarServico($url, \XMLWriter $xmlObject) {
        $endpointConfig = $this->comunicador->prepararConfiguracaoEndpoint($xmlObject->outputMemory());
        return $this->comunicador->chamar($url, $endpointConfig);
    }

    /** Converte toda o xml respondido em forma de string pelo serviço em um objeto DOMDocument
     * 
     * @param string $respostaString String de resposta
     * @return \DOMDocument
     * @throws \Exception
     */
    private function converterRespostaParaDOMDocument($respostaString) {
        if ($this->comunicador->ehSOAPFaultComoString($respostaString)) {
            throw $this->comunicador->converterSOAPFaultStringParaException($respostaString);
        }

        $XML = new \DOMDocument();
        $XML->loadXML($respostaString);

        return $XML;
    }

    /** Processa a resposta de uma chamada a um serviço de solicitação de tíquete de segurança
     * 
     * @param \DOMDocument $resposta Resposta de uma chamada a um serviço de solicitação de tíquete de segurança
     * @return \TIExpert\WSBoletoSantander\Ticket
     * @throws \Exception
     */
    private function processarRetornoParaTicket(\DOMDocument $resposta) {
        try {
            $ticket = $this->criarTiqueteAPartirDaResposta($resposta);
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
        $leitor = new LeitorSimplesXML($dom);
        $retCode = $leitor->getValorNo("retCode");

        if ($retCode === "0") {
            $ticket = new Ticket();
            $ticket->setAutenticacao($leitor->getValorNo("ticket"));
            return $ticket;
        }

        throw new \Exception("O serviço de inclusão de título do Santander retornou um erro. Código: " . $retCode);
    }

    /** Verifica se um título foi incluído com sucesso no Santander a partir da resposta do serviço de inclusão de título
     * 
     * @param \DOMDocument $dom Documento XML representando a resposta do serviço
     * @return boolean
     * @throws \Exception
     */
    private function tituloFoiIncluidoComSucesso(\DOMDocument $dom) {
        try {
            $this->lancarExceptionSeRespostaForSOAPFault($dom);
            $this->processarErros($dom);
        } catch (\Exception $e) {
            throw $e;
        }

        return true;
    }

    /** Tenta converter um documento XML de reposta em uma instância da classe Boleto
     * 
     * @param \DOMDocument $respostaXML Documento XML respondido pelo serviço
     * @return \TIExpert\WSBoletoSantander\Boleto
     */
    private function converterRespostaParaBoleto(\DOMDocument $respostaXML) {
        $this->lancarExceptionSeRespostaForSOAPFault($respostaXML);
        $this->processarErros($respostaXML);

        $boleto = new Boleto();
        $boleto->carregarPorXML($respostaXML);
        return $boleto;
    }

    /** Verifica se a resposta de um serviço é um SOAPFault. Em caso verdadeiro, uma Exception é lançada com a mensagem contendo a faultstring.
     * 
     * @param \DOMDocument $dom Documento XML representando a resposta do serviço
     * @return NULL
     * @throws \Exception
     */
    private function lancarExceptionSeRespostaForSOAPFault(\DOMDocument $dom) {
        $leitor = new LeitorSimplesXML($dom);
        try {
            $faultString = $leitor->getValorNo("faultstring");
            throw new \Exception($faultString);
        } catch (\Exception $e) {
            return;
        }
    }

    /** Lança uma exceção contendo todos os erros informados na resposta do serviço de inclusão de título
     * 
     * @param \DOMDocument $dom Documento XML representando a resposta do serviço
     * @throws \Exception
     */
    private function processarErros(\DOMDocument $dom) {
        $leitor = new LeitorSimplesXML($dom);
        $errosStr = $leitor->getValorNo("descricaoErro");

        $errorDesc = $this->gerarArrayDeErrosAPartirDaString($errosStr);

        if (count($errorDesc) > 0) {
            throw new \Exception("Serviço do Santander retornou os seguintes erros: " . implode("; ", $errorDesc));
        }
    }

    /** Gera um array a partir de uma string com layout definido pelo Santander contendo a descrição de todos os erros encontrados na requisição do serviço
     * 
     * @param string $errosStr String de formato determinado contendo os erros encontrados na requisição do serviço
     * @return array
     */
    private function gerarArrayDeErrosAPartirDaString($errosStr) {
        $errosRetornados = array();

        if ($errosStr != "") {
            $erros = explode("\n", $errosStr);
            foreach ($erros as $erro) {
                list($codigo, $descricao) = explode("-", $erro);

                if (trim($codigo) == "00000") {
                    break;
                }

                $errosRetornados[] = trim($descricao);
            }
        }

        return $errosRetornados;
    }

}
