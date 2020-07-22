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
   * Arquivo atual a ser processado
   *
   * @var string
   */
  protected $current;

  public function __construct()
  {
    $this->archive = new ZipArchive();
  }

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

  public function make()
  {
    $this->archive->open($this->temporaryFile, ZipArchive::CREATE);
    return $this;
  }

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

  public function clean()
  {
    // Fecha o arquivo ZIP
    $this->archive->close();

    // Remove do diretório temporário
    unlink($this->temporaryFile);

    return $this;
  }
}
