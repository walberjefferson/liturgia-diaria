<?php

/**
 * Algo que pode ser lido
 */
class Leitura {
    private $titulo = "";
    private $texto = "";

    public function __construct(DOMElement $titulo, DOMElement $texto) {
        $this->titulo = $titulo;
        $this->texto = $texto;
    }

    public function toArray() {
        return array(
            'titulo'=> HTMLUtils::removeBreak($this->titulo->nodeValue),
            'texto' => HTMLUtils::DOMinnerHTML($this->texto)
        );
    }
}