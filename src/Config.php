<?php

/*
 * Copyright 2016 Denys Xavier.
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
 * Classe de acesso e manipulação às configurações do WS Boletos Santander contidas no arquivo de configuração
 * 
 * @author Denys Xavier <equipe@tiexpert.net>
 */
class Config {

    /** @property string $caminhoArquivoConfig Caminho para o arquivo de configuração utilizado. */
    private static $caminhoArquivoConfig = __DIR__ . "/config.ini";

    /** @property array $config Array de configurações unificado. */
    private static $config = array("convenio" => array("banco_padrao" => "0033",
            "convenio_padrao" => "")
    );

    /** @property Config $instance Ponteiro para a instância única de Config */
    private static $instance;

    /** Cria uma nova instância de Config
     * 
     * @param string $caminhoArquivoConfig Caminho para o arquivo de configuração utilizado. Se nada for informado, então, será considerado o arquivo config.ini da mesma pasta que o arquivo da classe Config.
     */
    private function __construct() {
        $this->carregarConfiguracao();
    }

    /** Obtém uma instância de Config
     *
     * @return Config
     */
    public static function getInstance() {
        if (is_null(self::$instance)) {
            self::$instance = new Config();
        }
        return self::$instance;
    }

    /** Obtém o caminho do arquivo de configuração que está sendo usado
     * 
     * @return string
     */
    public static function getCaminhoArquivoConfig() {
        return self::$caminhoArquivoConfig;
    }

    /** Define qual o caminho do arquivo de configuração a ser usado.
     * 
     * Caso uma instância de Config já tenha sido criada com o método <code>getInstance()</code>, então, não é mais possível alterar esta propriedade e o novo valor é ignorado.
     * 
     * @param string $caminhoArquivoConfig Caminho para o arquivo de configuração a ser utilizado ou criado
     */
    public static function setCaminhoArquivoConfig($caminhoArquivoConfig) {
        if (!is_null(self::$instance) && self::$caminhoArquivoConfig != $caminhoArquivoConfig) {
            $this->carregarConfiguracao();
        }
        self::$caminhoArquivoConfig = $caminhoArquivoConfig;
    }

    /** Obtém o valor da chave informada dentro do grupo convenio
     *
     * @param string $chave Nome da chave da qual o valor deve ser retornado
     * @return mixed
     */
    public function getConvenio($chave) {
        return $this->getOpcao("convenio", $chave);
    }

    /** Obtém o valor da chave informada dentro do grupo informado
     *
     *
     * @return mixed
     */
    public function getOpcao($grupo, $chave) {
        if (isset(self::$config[$grupo][$chave])) {
            return self::$config[$grupo][$chave];
        } else {
            throw new \InvalidArgumentException("O valor da chave " . $chave . " pertencente ao grupo " . $grupo . " não existe.");
        }
    }

    /** Carrega as configurações a partir de um arquivo INI */
    private static function carregarConfiguracao() {
        if (file_exists(self::$caminhoArquivoConfig)) {
            self::$config = parse_ini_file(self::$caminhoArquivoConfig, true);
        } else {
            self::criarArquivoDeConfiguracao();
        }
    }

    /** Cria um novo arquivo de configuração no caminho especificado baseado nos dados padrões do array $config
     * 
     * @throws \Exception se acontecer algum problema ao tentar manipular o arquivo.
     */
    private static function criarArquivoDeConfiguracao() {
        $handler = @fopen(self::$caminhoArquivoConfig, "w");
        if ($handler) {
            $ini = self::converterArrayDeConfiguracaoParaINI();

            fwrite($handler, $ini);
            fclose($handler);
        } else {
            $err = error_get_last();
            throw new \Exception("Não foi possível encontrar o arquivo de configuração e, ao tentar criá-lo, ocorreu o seguinte erro: " . $err["message"]);
        }
    }

    /** Converte o array $config da instância atual para uma string formatada pronta para ser gravada em um arquivo .ini
     *
     * @return string
     */
    private static function converterArrayDeConfiguracaoParaINI() {
        $ini = "; Este arquivo foi criado automaticamente ao tentar acessar as configurações do WSBoletoSantander
; Se quiser uma versão com as opções comentadas, acesse: https://github.com/DenysXavier/WSBoletoSantander
; Arquivo de configuração gerado em " . date("d/m/Y H:i:s") . PHP_EOL;

        foreach (self::$config as $grupo => $chaves) {
            $ini .= PHP_EOL . self::converterStringEmGrupoDeConfiguracaoINI($grupo);
            foreach ($chaves as $chave => $valor) {
                $ini .= self::converterChaveEValorEmLinhaDeOpcaoINI($chave, $valor);
            }
        }

        return $ini;
    }

    /** Formata uma string qualquer em um grupo de configuração.
     * 
     * Por exemplo, uma string "XPTO" seria formatada como <code>[XPTO]</code>.
     * 
     * @param string $string String a ser convertida em um formato de grupo
     * @return string
     */
    private static function converterStringEmGrupoDeConfiguracaoINI($string) {
        return "[" . $string . "]" . PHP_EOL;
    }

    /** Formata duas strings quaisquer em uma linha de opção de configuração.
     * 
     * Por exemplo: uma string de chave "ordenar" e outra string de valor "ASC" resulta na linha de configuração <code>ordernar = "ASC"</code>.
     * 
     * @param string $chave A string que será usada como chave de configuração
     * @param string $valor A string que será usada como valor da chave de configuração
     * @return string
     */
    private static function converterChaveEValorEmLinhaDeOpcaoINI($chave, $valor) {
        $str = $chave . " = ";
        if (is_string($valor)) {
            $valor = '"' . $valor . '"';
        }
        $str .= $valor . PHP_EOL;
        return $str;
    }

}
