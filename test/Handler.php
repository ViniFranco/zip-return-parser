<?php

namespace Vini\ZipReturnParser\Tests;

use PHPUnit\Framework\TestCase;
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
}
