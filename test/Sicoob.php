<?php

namespace Vini\ZipReturnParser\Tests;

use PHPUnit\Framework\TestCase;
use Vini\ZipReturnParser\Responses\Sicoob as SicoobResponse;

class Sicoob extends TestCase
{
  public function testSicoob()
  {
    // Cria uma resposta de teste
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
