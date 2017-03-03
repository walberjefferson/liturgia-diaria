<?php

/**
 * Class Leituras
 */
class Leituras
{
    /*
     * Segunda a Sabado indices:
     * [0] => primeira leitura
     * [1] => salmo
     * [2] => evangelho
     * [3] => reflexão
     *
     * Domingo indices:
     * [0] => primeira leitura
     * [1] => salmo
     * [2] => segunda leitura
     * [3] => evangelho
     */
    private $leituras;

    public function __construct($indices)
    {
        $this->leituras = $indices['leiturasDoDia'];
//        print_r($this->indices());
    }

    private function indices()
    {
        return array_keys($this->leituras);
    }

    /**
     * @return mixed
     */
    public function titulo($indice)
    {
        return $this->indices()[(int)$indice];
    }

    /**
     * @return mixed
     */
    public function texto($indice)
    {
        return $this->leituras[$this->indices()[(int)$indice]];
    }

}