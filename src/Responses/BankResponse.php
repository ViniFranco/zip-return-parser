<?php

namespace Vini\ZipReturnParser\Responses;

abstract class BankResponse
{
    /**
     * Resposta em formato de texto
     *
     * @var string
     */
    protected $rawResponse;

    public function __construct(string $input)
    {
        $this->rawResponse = $input;
    }

    /**
     * @return static
     */
    public function parse($fromFormat): self
    {
        $json =
      $fromFormat !== null
      ? $fromFormat
      : json_decode($this->rawResponse, true);

        if ($json) {
            foreach ($json as $k => $v) {
                $this->$k = $v;
            }

            return $this;
        }

        return $this;
    }
}
