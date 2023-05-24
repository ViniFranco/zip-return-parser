<?php

namespace Vini\ZipReturnParser\Responses;

use Vini\ZipReturnParser\Responses\BankResponse;

/**
 * Classe responsável por fazer o tratamento da resposta da API do Sicoob.
 * 
 * @package ViniFranco\ZipReturnParser
 * @subpackage Responses
 * @author Vini Franco <email@vinifranco.com.br>
 * 
 * @property string nomeArquivo Nome do arquivo retornado pela API.
 * @property string arquivo Conteúdo do arquivo em base64.
 */
class Sicoob extends BankResponse
{
  /**
   * Uma resposta formatada para manter conformidade com o padrão da API do banco.
   *
   * @var array
   */
  protected $formattedResponse;

  public function __construct($input)
  {
    parent::__construct($input);
  }

  /**
   * Formata a resposta para manter conformidade com o padrão da API do banco.
   *
   * @return Sicoob
   */
  public function format()
  {
    $formatted = json_decode($this->rawResponse, true)['resultado'];

    $this->formattedResponse = $formatted;

    $this->parse($this->formattedResponse);

    return $this;
  }
}
