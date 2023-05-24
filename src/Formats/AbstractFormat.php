<?php

namespace Vini\ZipReturnParser\Formats;

use Vini\ZipReturnParser\Contracts\FileFormat;

/**
 * Classe que representa a abstração de um formato de arquivo qualquer.
 */
abstract class AbstractFormat implements FileFormat
{
    /**
     * Conteúdo do arquivo.
     * 
     * @var string
     */
    protected $data;

    public function __construct(string $data)
    {
        $this->data = $data;
    }

    public function getRawString(): string
    {
        return $this->data;
    }

    public function getBase64(): string
    {
        return base64_encode($this->data);
    }
}
