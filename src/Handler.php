<?php

namespace Vini\ZipReturnParser;

use Carbon\Carbon;
use LogicException;
use OutOfRangeException;
use Vini\ZipReturnParser\Factories\FileFormatFactory;
use ZipArchive;

/**
 * Classe responsável por fazer o tratamento do arquivo em formato ZIP
 *
 * @package ViniFranco\ZipReturnParser
 * @author Vini Franco <email@vinifranco.com.br>
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
     * Canminho do arquivo atual a ser processado
     *
     * @var string
     */
    protected $current;

    /**
     * Dados do arquivo atual a ser processado
     *
     * @var string
     */
    protected $currentData;

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
            '.zip';

        // Salva o conteúdo em um arquivo temporário
        $temporary = sys_get_temp_dir() . DIRECTORY_SEPARATOR . $temporaryFileName;

        // Coloca o conteúdo dentro do arquivo temporário
        file_put_contents($temporary, base64_decode($base64));

        // Salva o caminho para possibilitar a deleção posterior do arquivo
        $this->base64TemporaryFile = $temporary;

        return $this;
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
     * Abre o arquivo ZIP temporário.
     *
     * @throws LogicException Caso não haja nenhum arquivo adicionado.
     */
    public function make(): self
    {
        if (empty($this->temporaryFile) && empty($this->base64TemporaryFile)) {
            throw new LogicException('Não há nenhum arquivo adicionado.');
        }

        if (empty($this->base64TemporaryFile) && !empty($this->temporaryFile)) {
            $this->archive->open($this->temporaryFile, ZipArchive::CREATE);
            return $this;
        }

        $this->archive->open($this->base64TemporaryFile, ZipArchive::CREATE);
        return $this;
    }

    /**
     * Define o arquivo atual a ser usado para trabalhar
     *
     * @param integer $index
     */
    public function use(int $index = 0): self
    {
        if ($this->exists($index)) {
            // Define o nome do arquivo sendo usado.
            $this->current = $this->archive->getNameIndex($index);

            // Puxa o conteúdo do arquivo.
            $this->currentData = $this->archive->getFromIndex($index);

            return $this;
        }

        throw new OutOfRangeException('Índice de arquivo inválido fornecido.');
    }

    /**
     * Retorna uma instância da classe de tratamento do formato do arquivo
     * ou false em caso de erro ou arquivo vazio.
     *
     * @return Factories\FileFormat
     * @throws LogicException caso não haja nenhum arquivo selecionado.
     */
    public function toFormat($format = null)
    {
        if (!empty($this->current)) {
            // Verifica o tipo MIME do arquivo. Caso não haja arquivo comum, usa o base 64.
            $mime = mime_content_type(
                'zip://' . $this->temporaryFile ?? $this->base64TemporaryFile . '#' . $this->current
            );

            return FileFormatFactory::create($format ?? $mime, $this->currentData);
        }

        throw new LogicException('Não há arquivo selecionado.');
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
        if (!empty($this->temporaryFile)) {
            unlink($this->temporaryFile);
        }

        // Caso exista, remove o arquivo temporário de base64
        if (!empty($this->base64TemporaryFile)) {
            unlink($this->base64TemporaryFile);
        }

        return $this;
    }

    /**
     * Retorna o caminho para o arquivo temporário gerado
     * a partir da string de dados bruta.
     *
     * @return string
     */
    public function getTemporaryFilePath()
    {
        return $this->temporaryFile;
    }

    /**
     * Retorna o caminho para o arquivo temporário gerado a partir
     * da string base64 do arquivo ZIP.
     *
     * @return string
     */
    public function getBase64TemporaryFilePath()
    {
        return $this->base64TemporaryFile;
    }

    /**
     * Usa o primeiro índice do arquivo ZIP.
     */
    public function first(): self
    {
        return $this->use();
    }

    /**
     * Usa o último índice do arquivo ZIP.
     */
    public function last(): self
    {
        return $this->use($this->archive->count() - 1);
    }

    /**
     * Retorna se um índice existe no arquivo ZIP.
     *
     * @param integer $index
     * @return boolean
     */
    public function exists(int $index): bool
    {
        return $this->archive->count() && $this->archive->count() >= $index + 1;
    }
}
