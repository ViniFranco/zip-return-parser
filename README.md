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

- PHP >=7.2.5.
- [PHP extensão ZIP](https://www.php.net/manual/pt_BR/zip.installation.php)
- [PHP extensão Mbstring](https://www.php.net/manual/pt_BR/mbstring.installation.php)

## Motivação

Algumas APIs de bancos retornam os arquivos - seja de movimentação, remessa ou qualquer outro tipo de retorno - zipados, codificados em formato base64 em respostas de API. Para facilitar o processo, este pacote contém funções de processamento comum para tais arquivos.

### Uso

Exemplo com o formato da API do banco Sicoob, que tem um campo 'resultado' e um 'arquivo'
que está codificado em formato base64. O arquivo que está dentro do ZIP é um JSON:

```php
  use Vini\ZipReturnParser\Handler;
  use Vini\ZipReturnParser\Responses\Sicoob;
  use Vini\ZipReturnParser\Factories\FileFormatFactory;
  
  // ... busca o arquivo na API do banco: $respostaApi
  
  // Cria uma instância do handler
  $handler = new Handler();

  // Cria o formato de resposta
  $response = (new Sicoob($respostaApi))->format();

  // Passa o arquivo para o handler
  $handler->fromBase64($response->arquivo)->make();

  // Usa o primeiro arquivo e cria uma instância da classe que trata JSON automaticamente
  $file = $handler->first()->toFormat(FileFormatFactory::FORMAT_JSON);

  // Imprime na tela o conteúdo do arquivo (nesse passo qualquer outro processamento pode ser feito)
  var_dump($file->getDecoded());

  // Limpa os arquivos temporários do disco
  $handler->clean();
```

### Créditos

Agradeço a colaboração dos amigos nos testes e processo de elaboração da ideia que tornou esse pacote realidade:

- [Murilo Sandiego](https://github.com/murilosandiego)
- Altierres Washington

### Licença

MIT