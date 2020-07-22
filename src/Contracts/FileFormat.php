<?php

namespace Vini\ZipReturnParser\Contracts;

interface FileFormat
{
  public function getRawString();
  public function getBase64();
}
