<?php

namespace ViniFranco\ZipReturnParser\Tests;

use PHPUnit\Framework\TestCase;
use ViniFranco\ZipReturnParser\Responses\Sicoob as SicoobResponse;

class Sicoob extends TestCase
{
  public function testSicoob()
  {
    // Cria uma resposta de mentira
    $raw = json_encode([
      'resultado' => [
        'arquivo' => 'test',
        'nomeArquivo' => 'test.zip',
      ],
    ]);

    $response = (new SicoobResponse($raw))->format();

    $this->assertTrue($response->arquivo === 'test');
    $this->assertTrue($response->nomeArquivo === 'test.zip');
  }
}
