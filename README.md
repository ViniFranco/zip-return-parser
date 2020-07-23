# Interpretador para arquivos de retorno em ZIP

Pacote para ler arquivos de retorno de APIs de banking que estejam em
formato ZIP.

- [Interpretador para arquivos de retorno em ZIP](#interpretador-para-arquivos-de-retorno-em-zip)
  - [Requerimentos](#requerimentos)
  - [Motivação](#motivação)
    - [Uso](#uso)
    - [Créditos](#créditos)
    - [Licença](#licença)

## Requerimentos

- PHP 5.6 ou mais recente (PHP 7 recomendado)
- [PHP extensão ZIP](https://www.php.net/manual/pt_BR/zip.installation.php)
- [PHP extensão Mbstring](https://www.php.net/manual/pt_BR/mbstring.installation.php)

## Motivação

Algumas APIs de bancos retornam arquivos zipados, às vezes codificados em formato base64 em respostas de API. Para facilitar o processo, este pacote contém funções de processamento
comum para tais arquivos.

### Uso

Exemplo com o formato da API do banco Sicoob, que tem um campo 'resultado' e um 'arquivo'
que está codificado em formato base64. O arquivo que está dentro do ZIP é um JSON:

```php
  // ... busca o arquivo na API do banco: $respostaApi
  
  // Cria uma instância do handler
  $handler = new \Vini\ZipReturnParser\Handler();

  // Cria o formato de resposta
  $response = (new \Vini\ZipReturnParser\Responses\Sicoob($respostaApi))->format();

  // Passa o arquivo para o handler
  $handler->fromBase64($response->arquivo)->make();

  // Usa o primeiro arquivo e cria uma instância da classe que trata JSON automaticamente
  $file = $handler->use(0)->toFormat();

  // Imprime na tela o conteúdo do arquivo (nesse passo qualquer outro processamento pode ser feito)
  var_dump($file->getDecoded());

  // Limpa os arquivos temporários do disco
  $handler->clean();
```

### Créditos

O pacote foi desenvolvido pelo time do projeto [DConta](https://dconta.com.br)
da [DFranquias](https://dfranquias.com):

- [Murilo Sandiego](https://github.com/murilosandiego)
- [Vini Franco](https://github.com/ViniFranco)
- Altierres Washington

### Licença

MIT