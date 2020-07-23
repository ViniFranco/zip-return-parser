<?php

namespace Vini\ZipReturnParser\Tests;

use PHPUnit\Framework\TestCase;
use ZipArchive;
use Vini\ZipReturnParser\Handler as ZipHandler;
class Handler extends TestCase
{
  public function testBadMethodCallInMake()
  {
    // Cria o handler
    $handler = new ZipHandler();

    // Espera por uma exceção BadMethodCall
    $this->expectException(\BadMethodCallException::class);

    $handler->make();
  }

  public function testInvalidArgumentExceptionInUse()
  {
    // Cria o handler
    $handler = new ZipHandler();

    // Espera por uma exceção InvalidArgument
    $this->expectException(\InvalidArgumentException::class);

    $handler->use(0);
  }

  public function testFalseReturnInToFormat()
  {
    // Cria o handler
    $handler = new ZipHandler();

    $false = $handler->toFormat();

    $this->assertEquals(false, $false);
  }

  public function testClean()
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
    $handler = new ZipHandler();

    // Testa a limpeza
    $handler
      ->fromBase64($base64)
      ->make()
      ->clean();

    // Verifica se o arquivo de fato foi removido
    $this->assertFalse(file_exists($handler->getBase64TemporaryFilePath()));
  }
}
