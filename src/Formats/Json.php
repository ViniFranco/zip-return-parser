<?php

namespace Vini\ZipReturnParser\Formats;

use Vini\ZipReturnParser\Contracts\FileFormat;

class Json implements FileFormat
{
  /**
   * Dados do arquivo.
   *
   * @var string
   */
  protected $data;

  public function __construct($data)
  {
    $this->data = $data;
  }

  /**
   * Retorna o arquivo codificado em base64
   *
   * @return string
   */
  public function getBase64()
  {
    return base64_encode($this->data);
  }

  /**
   * Retorna a string bruta contendo os dados do arquivo
   *
   * @return string
   */
  public function getRawString()
  {
    return $this->data;
  }

  /**
   * Retorna os dados codificados em uma string JSON ou false em caso de erro
   *
   * @param boolean $escape
   * @return string|false
   */
  public function getEncoded()
  {
    return json_encode(
      $this->utf8($this->data),
      JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT,
    );
  }

  public function getDecoded()
  {
    return json_decode($this->data, true);
  }

  /**
   * Converte a string de entrada em UTF-8. Retorna false em caso de arquivo vazio.
   *
   * @param string $data
   * @return string|false
   */
  protected function utf8($data)
  {
    if (!$data) {
      return false;
    }

    return mb_check_encoding($data, 'UTF-8')
      ? $data
      : mb_convert_encoding($data, 'UTF-8');
  }
}
