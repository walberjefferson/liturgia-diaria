<?php
/**
 * Algo que pode ser lido
 */
class Leitura {
    private $titulo = "";
    private $texto = "";

    public function __construct($titulo, $texto) {
        $this->titulo = $titulo;
        $this->texto = $texto;
    }

    public function toArray() {
        return array('titulo'=> $this->titulo, 'texto'=> $this->texto);
    }
}
