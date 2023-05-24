<?php

namespace Vini\ZipReturnParser\Tests;

use PHPUnit\Framework\TestCase;
use Vini\ZipReturnParser\Responses\Sicoob as Sicoob;

class SicoobTest extends TestCase
{
  public function testSicoobResponse()
  {
    // Cria uma resposta de teste
    $raw = json_encode([
      'resultado' => [
        'arquivo' => 'test',
        'nomeArquivo' => 'test.zip',
      ],
    ]);

    $response = (new Sicoob($raw))->format();

    $this->assertTrue($response->arquivo === 'test');
    $this->assertTrue($response->nomeArquivo === 'test.zip');
  }
}
