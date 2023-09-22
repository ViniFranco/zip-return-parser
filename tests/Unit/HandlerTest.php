<?php

namespace Vini\ZipReturnParser\Tests;

use LogicException;
use OutOfRangeException;
use PHPUnit\Framework\TestCase;
use ZipArchive;
use Vini\ZipReturnParser\Handler as ZipHandler;


class HandlerTest extends TestCase
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
   * @var ZipHandler
   */
  protected $handler;

  protected function setUp()
  {
    $this->handler = new ZipHandler();

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

  public function testLogicExceptionInMake()
  {
    // Espera por uma exceção
    $this->expectException(LogicException::class);

    $this->handler->make();
  }

  public function testOutOfRangeExceptionInUse()
  {
    // Espera por uma exceção
    $this->expectException(OutOfRangeException::class);

    $this->handler->fromBase64(static::$base64)->make()->use(1);
  }

  public function testLogicExceptionInToFormat()
  {
    $this->expectException(LogicException::class);

    $this->handler->toFormat();
  }

  public function testClean()
  {
    // Testa a limpeza
    $this->handler
      ->fromBase64(static::$base64)
      ->make()
      ->clean();

    // Verifica se o arquivo de fato foi removido
    $this->assertFalse(file_exists($this->handler->getBase64TemporaryFilePath()));
  }
}
