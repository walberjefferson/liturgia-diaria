<?php

/**
 * Gerador de leitura
 */
class LeituraBuilder {

    private $leitura;

    public function LeituraBuilder(DOMElement $leitura) {
        $this->leitura = $leitura;
    }

    public function gerar() {
        return new Leitura($this->titulo(), $this->texto());
    }

    private function titulo() {
        return $this->leitura->getElementsByTagName("h3")[0];
    }

    private function texto() {
        //foreach ($this->leitura->childNodes as $node)
        //    print_r($node);

        // FIXME - O número [5] é sem sentido
        return $this->leitura->childNodes[5];
    }
}