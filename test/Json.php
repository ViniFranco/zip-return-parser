<?php

namespace Vini\ZipReturnParser\Tests;

use Vini\ZipReturnParser\Handler;
use Vini\ZipReturnParser\Formats\Json as JsonFileFormat;
use ZipArchive;
use PHPUnit\Framework\TestCase;

class Json extends TestCase
{
  public function testJson()
  {
    // Cria um arquivo qualquer em formato json
    $data = [
      'json' => 'test',
    ];

    $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

    // Cria o arquivo ZIP
    $zip = new ZipArchive();

    // Adiciona o arquivo JSON dentro do ZIP
    if ($zip->open('test.zip', ZipArchive::CREATE) === true) {
      $zip->addFromString('test.json', $jsonString);
      $zip->close();
    }

    // Chama o handler
    $handler = new Handler();

    // Abre o arquivo ZIP
    $handler->fromData(file_get_contents('test.zip'))->make();

    // Remove o arquivo do disco
    unlink('test.zip');

    // Usa o primeiro arquivo

    /**
     * @var JsonFileFormat
     */
    $instance = $handler->use(0)->toFormat();

    // Verifica se retornou a instância da classe Json
    $this->assertTrue($instance instanceof JsonFileFormat);

    // Verifica se o conteúdo do arquivo decodificado é igual ã entrada
    $this->assertEquals($instance->getDecoded(), $data);
  }

  public function testJsonFromBase64()
  {
    // Cria um arquivo qualquer em formato json
    $data = [
      'json' => 'test',
    ];

    $jsonString = json_encode($data, JSON_UNESCAPED_UNICODE);

    // Cria o arquivo ZIP
    $zip = new ZipArchive();

    // Adiciona o arquivo JSON dentro do ZIP
    if ($zip->open('testb64.zip', ZipArchive::CREATE) === true) {
      $zip->addFromString('test.json', $jsonString);
      $zip->close();
    }

    // Transforma em base64 o conteúdo do test.zip
    $base64 = base64_encode(file_get_contents('testb64.zip'));

    // Remove o test.zip
    unlink('testb64.zip');

    // Chama o handler
    $handler = new Handler();

    // Adiciona a entrada ao handler

    /**
     * @var JsonFileFormat
     */
    $instance = $handler
      ->fromBase64($base64)
      ->make()
      ->use(0)
      ->toFormat();

    // Verifica se existe a instância de JsonFileFormat
    $this->assertTrue($instance instanceof JsonFileFormat);

    // Se o arquivo não está vazio
    $this->assertNotEmpty($instance->getDecoded());

    // E por último se o conteúdo foi corretamente retornado
    $this->assertEquals($data, $instance->getDecoded());
  }
}
