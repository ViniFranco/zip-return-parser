<?php

namespace ViniFranco\ZipReturnParser\Tests;

use ViniFranco\ZipReturnParser\Handler;
use ViniFranco\ZipReturnParser\Formats\Json as JsonFileFormat;
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

    // Usa o primeiro arquivo
    $instance = $handler->use(0)->toFormat();

    // Verifica se retornou a instância da classe Json
    $this->assertTrue($instance instanceof JsonFileFormat);
  }
}
