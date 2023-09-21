<?php

namespace Vini\ZipReturnParser\Factories;

use Vini\ZipReturnParser\Formats\Json;
use DomainException;

/**
 * Classe responsável por retornar instâncias apropriadas para tratar cada
 * tipo de formato de arquivo que possa estar dentro do retorno.
 * @package ViniFranco\ZipReturnParser\Formats
 * @author Vini Franco <email@vinifranco.com.br>
 */
class FileFormatFactory
{
  public const FORMAT_JSON = 'application/json';

  /**
   * Retorna a classe de tratamento do formato de arquivo.
   *
   * @param string $format
   *
   * @throws DomainException
   */
  public static function create(
    string $format = 'application/json',
    string $data
  ): Json {
    switch ($format) {
      case 'application/json':
        return new Json($data);
      default:
        throw new DomainException('Tipo de arquivo não suportado.');
    }
  }
}
