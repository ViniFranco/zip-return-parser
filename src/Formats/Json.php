<?php

namespace Vini\ZipReturnParser\Formats;

/**
 * Classe que representa um arquivo JSON.
 */
class Json extends AbstractFormat
{
  /**
   * Retorna os dados codificados em uma string JSON ou false em caso de erro
   *
   * @param boolean $escape
   * @return string|false
   */
    public function getEncoded()
    {
        return json_encode($this->utf8($this->data));
    }

    /**
     * Retorna os dados do arquivo decodificados em forma de array associativo.
     *
     * @return array
     */
    public function getDecoded(): array
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
