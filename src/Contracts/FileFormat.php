<?php

namespace Vini\ZipReturnParser\Contracts;

/**
 * Interface comum aos formatos de arquivo suportados.
 */
interface FileFormat
{
    /**
     * Retorna a string crua com os dados do arquivo.
     *
     * @return string
     */
    public function getRawString(): string;

    /**
     * Retorna os conteúdos do arquivo em base64.
     *
     * @return string
     */
    public function getBase64(): string;
}
