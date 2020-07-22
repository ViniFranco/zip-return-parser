<?php

namespace ViniFranco\ZipReturnParser;

use ViniFranco\ZipReturnParser\Formats\FileFormatFactory;
use ZipArchive;
use Carbon\Carbon;

/**
 * Classe responsável por fazer o tratamento do arquivo em formato ZIP
 * @package ViniFranco\ZipReturnParser
 * @author Vini Franco <email@vinifranco.com.br>
 * @SuppressWarnings(PHPMD.StaticAccess)
 */
class Handler
{
  /**
   * Instância da classe ZipArchive
   *
   * @var ZipArchive
   */
  protected $archive;

  /**
   * Caminho para o arquivo temporário
   *
   * @var string
   */
  protected $temporaryFile;

  /**
   * Caminho para o arquivo temporário em base64
   *
   * @var string
   */
  protected $base64TemporaryFile;

  /**
   * Arquivo atual a ser processado
   *
   * @var string
   */
  protected $current;

  public function __construct()
  {
    $this->archive = new ZipArchive();
  }

  /**
   * Adiciona o arquivo a partir de uma string base64
   *
   * @param string $base64
   * @return Handler
   */
  public function fromBase64($base64)
  {
    $temporaryFileName =
      'zip-return-parser-' .
      Carbon::now('America/Fortaleza')->getTimestamp() .
      '.dat';

    // Salva o conteúdo em um arquivo temporário
    $temporary = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $temporaryFileName;

    // Coloca o conteúdo dentro do arquivo temporário
    file_put_contents($temporary, base64_decode($base64));

    // Salva o caminho para possibilitar a deleção posterior do arquivo
    $this->base64TemporaryFile = $temporary;

    return $this->fromData(file_get_contents($temporary));
  }

  /**
   * Salva um arquivo temporário no sistema para tratamento
   *
   * @param string $data
   * @return Handler
   */
  public function fromData($data)
  {
    // Gera um nome de arquivo temporário
    $temporaryFileName =
      'zip-return-parser-' .
      Carbon::now('America/Fortaleza')->getTimestamp() .
      '.zip';

    // Salva o conteúdo em um arquivo temporário
    $temporary = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $temporaryFileName;

    // Coloca o conteúdo dentro do arquivo temporário
    file_put_contents($temporary, $data);

    // Define o nome de arquivo temporário para esta instância
    $this->temporaryFile = $temporary;

    return $this;
  }

  /**
   * Abre o arquivo ZIP temporário
   *
   * @return Handler
   */
  public function make()
  {
    $this->archive->open($this->temporaryFile, ZipArchive::CREATE);
    return $this;
  }

  /**
   * Define o arquivo atual a ser usado para trabalhar
   *
   * @param integer $index
   * @return Handler
   */
  public function use(int $index = 0)
  {
    $this->current = $this->archive->getNameIndex($index);
    return $this;
  }

  /**
   * Retorna uma instância da classe de tratamento do formato do arquivo
   * ou false em caso de erro ou arquivo vazio.
   * @return FileFormatFactory|false
   */
  public function toFormat()
  {
    if (!empty($this->current)) {
      $mime = mime_content_type(
        'zip://' . $this->temporaryFile . '#' . $this->current,
      );
      return FileFormatFactory::create($mime, $this->current);
    }

    return false;
  }

  /**
   * Remove o arquivo temporário do sistema
   *
   * @return Handler
   */
  public function clean()
  {
    // Fecha o arquivo ZIP
    $this->archive->close();

    // Remove do diretório temporário
    unlink($this->temporaryFile);

    // Caso exista, remove o arquivo temporário de base64
    if (!empty($this->base64TemporaryFile)) {
      unlink($this->base64TemporaryFile);
    }

    return $this;
  }
}
