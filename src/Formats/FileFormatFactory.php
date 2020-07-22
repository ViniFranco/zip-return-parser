<?php

namespace ViniFranco\ZipReturnParser\Formats;

/**
 * Classe responsável por retornar instâncias apropriadas para tratar cada
 * tipo de formato de arquivo que possa estar dentro do retorno.
 * @package ViniFranco\ZipReturnParser\Formats
 * @author Vini Franco <email@vinifranco.com.br>
 */
class FileFormatFactory
{
  /**
   * Retorna a classe de tratamento do formato de arquivo.
   *
   * @param string $format
   * @return FileFormat
   * @throws \InvalidArgumentException
   */
  public static function create(
    string $format = 'application/json',
    string $data
  ) {
    switch ($format) {
      case 'application/json':
        return new Json($data);
      default:
        throw new \InvalidArgumentException('Tipo de arquivo não suportado.');
    }
  }
}
