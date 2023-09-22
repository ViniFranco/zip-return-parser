<?php

namespace Vini\ZipReturnParser\Tests;

use Vini\ZipReturnParser\Handler;
use Vini\ZipReturnParser\Formats\Json as JsonFileFormat;
use ZipArchive;
use PHPUnit\Framework\TestCase;

class Json extends TestCase
{
  /**
   * Conteúdo de um arquivo zipado de teste, codificado em base64.
   *
   * @var string
   */
  protected static $base64;

  /**
   * Instância do Zip Handler.
   *
   * @var Handler
   */
  protected $handler;

  protected function setUp()
  {
    $this->handler = new Handler();

    $jsonString = json_encode(['json' => 'test'], JSON_UNESCAPED_UNICODE);

    // Cria o arquivo ZIP
    $zip = new ZipArchive();

    // Adiciona o arquivo JSON dentro do ZIP
    if ($zip->open('testb64.zip', ZipArchive::CREATE) === true) {
      $zip->addFromString('test.json', $jsonString);
      $zip->close();
    }


    // Transforma em base64 o conteúdo do test.zip
    static::$base64 = base64_encode(file_get_contents('testb64.zip'));
  }

  protected function tearDown()
  {
    // Remove o test.zip
    unlink('testb64.zip');
  }

  public function testJsonFromBase64()
  {

    /**
     * @var JsonFileFormat
     */
    $instance = $this->handler
      ->fromBase64(static::$base64)
      ->make()
      ->first()
      ->toFormat('application/json');

    // Verifica se existe a instância de JsonFileFormat
    $this->assertTrue($instance instanceof JsonFileFormat);

    // Se o arquivo não está vazio
    $this->assertNotEmpty($instance->getDecoded());

    // E por último se o conteúdo foi corretamente retornado
    $this->assertEquals(['json' => 'test'], $instance->getDecoded());
  }
}
