<p align="center">
  <a href="https://travis-ci.org/DenysXavier/WSBoletoSantander"><img alt="Build Status" src="https://travis-ci.org/DenysXavier/WSBoletoSantander.svg?branch=master"></a>
  <a href="https://coveralls.io/github/DenysXavier/WSBoletoSantander?branch=master"><img alt="Coverage Status" src="https://coveralls.io/repos/github/DenysXavier/WSBoletoSantander/badge.svg?branch=master"></a>
  <a href="https://packagist.org/packages/tiexpert/ws-boleto-santander"><img alt="Latest Stable Version" src="https://poser.pugx.org/tiexpert/ws-boleto-santander/v/stable"></a>
  <a href="https://packagist.org/packages/tiexpert/ws-boleto-santander"><img alt="Total Downloads" src="https://poser.pugx.org/tiexpert/ws-boleto-santander/downloads"></a>
  <a href="https://packagist.org/packages/tiexpert/ws-boleto-santander"><img alt="License" src="https://poser.pugx.org/tiexpert/ws-boleto-santander/license"></a>
  <br>
  <a href="https://insight.sensiolabs.com/projects/9ad566a3-f228-400d-8f4f-7405f23fe22a"><img alt="SensioLabsInsight" src="https://insight.sensiolabs.com/projects/9ad566a3-f228-400d-8f4f-7405f23fe22a/big.png"></a>
</p>

# WSBoletoSantander

WS Boleto Santander é um conjunto de classes criadas para facilitar a integração entre aplicativos feitos em PHP e a geração de boletos online no banco Santander.

## Índice

> * [Funcionalidades](#funcionalidades)
> * [Requisitos](#requisitos)
> * [Guia Básico de Uso](#guia-básico-de-uso)
>   * [Instalação da Biblioteca](#instalação-da-biblioteca)
>   * [Montagem do Boleto](#montagem-do-boleto)
>   * [Registrando o Boleto](#registrando-o-boleto)
> * [Questões Importantes](#questões-importantes)
> * [Mais Documentação](#mais-documentação)
> * [Licença](#licença)
> * [Aviso Legal](#aviso-legal)

## Funcionalidades

- [x] Inclusão/Registro de boletos
- [x] Sondagem de boletos registrados
- [x] Tratamento de erros de comunicação com o serviço do Santander

## Requisitos

* PHP 5.6 ou superior

Com as seguintes extensões ativas:

* cURL
* DOM
* XmlWriter

## Guia Básico de Uso

### Instalação da Biblioteca

WS Boleto Santander pode ser instalado via [Composer](https://getcomposer.org) usando o comando:

```console
composer require tiexpert/ws-boleto-santander
```

O Composer automaticamente verificará seu ambiente para determinar se seu servidor pode rodar a biblioteca WSBoletoSantander.

### Montagem do Boleto

Para registrar o boleto serão necessárias as seguintes classes:

```php
use TIExpert\WSBoletoSantander\Boleto;
use TIExpert\WSBoletoSantander\BoletoSantanderServico;
use TIExpert\WSBoletoSantander\ComunicadorCurlSOAP;
use TIExpert\WSBoletoSantander\Convenio;
use TIExpert\WSBoletoSantander\InstrucoesDeTitulo;
use TIExpert\WSBoletoSantander\Pagador;
use TIExpert\WSBoletoSantander\Titulo;
```
Agora, em seu script, defina o convênio.

```php
$convenio = new Convenio();
```

Se o arquivo de configuração estiver já pronto, as informações de convênio já estarão corretas. Caso não, você pode definí-las agora.

```php
$convenio->setCodigoBanco("0033");
$convenio->setCodigoConvenio("123456");
```

Então, defina as informações do pagador do boleto.

```php
$pagador = new Pagador($tipoDoc, $numeroDoc, $nome, $endereco, $bairro, $cidade, $UF, $CEP);
```

Se não desejar instanciar Pagador já com os dados é possível. Pagador tem um construtor padrão sem argumentos e cada propriedade tem um método set para definir valor como, por exemplo, setNome, setCidade, setCEP,etc.

Por fim, um objeto composto que são as informações do título bancário.

Comece definindo as instruções do título.

```php
$instrucoes = new InstrucoesDeTitulo();
```

As instruções mais corriqueiras são configuráveis via config.ini. Mas, todas as propriedades como `$multa`, `$multarApos`, `$juros`, `$tipoDesconto`, `$valorDesconto`, `$dataLimiteDesconto`, `$valorAbatimento`, `$tipoProtesto`, `$protestarApos`, `$baixarApos` tem métodos set.

Propriedades que são representações de data devem ser usadas instâncias de DateTime, ou uma string no formato "dmY". Exemplo: `$instrucao->setDataLimiteDesconto("28032017")`, ou seja, o desconto deve ser aplicado até 28/03/2017.

Enfim, usaremos essas instruções para compor as informações do título na classe Titulo.

```php
$titulo = new Titulo($valor, $nossoNumero, $seuNumero, $dataVencimento, $mensagem, $dataEmissao, $especie, $instrucoes);
```

Assim como as demais classes, todas as propriedades têm seus respectivos set.

Importante salientar que toda instância de Título deve conter uma instância de InstrucoesDeTitulo. Caso contrário, um erro acontecerá na exportação do XML.

Agora, com todas as partes prontas, basta montar o boleto.

```php
$boleto = new Boleto($convenio, $pagador, $titulo);
```

### Registrando o Boleto

Com o boleto já montado, ou seja, com seus objetos e campos populados, deve-se fazer o registro em dois passos: solicitar um tíquete de registro de boleto e depois ratificá-lo.

Primeiramente, vamos preparar o serviço injetando um comunicador no cliente do serviço.

```php
$comunicador = new ComunicadorCurlSOAP ();
$svc = new BoletoSantanderServico($comunicador);
```

Agora, devemos solicitar um tíquete com o método **`solicitarTicketInclusao`**.

> **Qualquer erro que o WebService retornar será lançado como um Exception pelo método.**

```php
$ticket = $svc->solicitarTicketInclusao($boleto);
```

Se nada deu errado, então, uma instância de Ticket é criada com uma autenticação de segurança do banco. Nesse momento, será necessário determinar um número sequencial único (NSU) que será a identificação de seu boleto. Para cada registro de boleto, este NSU deverá ser único por dia e por convênio, ou seja, não se pode usar o mesmo NSU no mesmo dia para o mesmo convênio.

```php
$ticket->setNsu(1);
```

Com o tíquete pronto, basta passá-lo como parâmetro no método **`incluirTitulo`**.

$resultado = $svc->incluirTitulo($ticket);

Este método retorna `true` em caso de registro com sucesso, ou `false`. Embora, em casos de falha, o mais provável é que seja lançado um Exception com o motivo da falha.

## Questões Importantes

Antes de qualquer tentativa de comunicação com o banco, deve-se primeiro pedir para eles cadastrarem seu certificado digital lá. Sem isso, não tem como o serviço do banco saber a autenticidade de quem o está requisitando.

Outra coisa, seu certificado digital também deve respeitar algumas regras.

Primeiro, ele deve ser do tipo cliente ou ambos, ou seja, ele deve de qualquer forma prover meios de comprovar sua identidade.

![Aba de informações gerais do certificado](http://i65.tinypic.com/wb2czc.jpg)

Além disso, seu certificado deve ter 4 informações importantes:

1) O tamanho da chave chave-pública deve ser de 2048 bits.

![Tamanho da chave-pública](http://i67.tinypic.com/2wecfad.jpg)

2) Deve conter número de série.

![Chave Serial](http://i67.tinypic.com/xen054.jpg)

3) Possuir uma impressão digital.

![Impressão Digital do Certificado](http://i67.tinypic.com/25iwtc1.jpg)

4) E, um Common Name.

![Common Name](http://i67.tinypic.com/ml49ar.jpg)

Para facilitar o processo de comunicação com o serviço do Santander, é interessante baixar o certificado da CA deles, que atualmente é Entrust Root Certificate Authority—G2.

Ele pode ser encontrado aqui: https://www.entrust.com/get-support/ssl-certificate-support/root-certificate-downloads/

Também será necessário exportar seu certificado digital para o formato PEM.

Com ambos os arquivos, configure-os no arquivo *config.ini* do WSBoletoSantander.

Exemplo:

```ini
[certificado]
arquivo = "/var/www/html/meu_certificado_digital.pem"
senha = "Senha do meu certificado"
tipo_arquivo = "PEM"
arquivo_ca = "/var/www/html/entrust_g2_ca.cer"
```

## Mais Documentação

Em breve, na Wiki do projeto.

## Licença

WS Boleto Santander é distribuído sob a Licença Apache 2.0 e não pode ser usado de forma diferente que a expressa por essa licença.

Maiores informações, acesse http://www.apache.org/licenses/LICENSE-2.0.

## Aviso Legal

O autor deste projeto não tem nenhuma afiliação, vínculo ou qualquer outra relação com o banco Santander S.A.

O software é oferecido aqui "como está" e nenhuma garantia é proferida. Portanto, o uso deste software é de inteira responsabilidade do utilizador.
