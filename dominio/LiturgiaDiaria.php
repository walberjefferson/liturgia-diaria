<?php

class LiturgiaDiaria {
    private $titulo;
    private $pagina;
    private $data;

    private $finder;

    public function __construct($dia, $mes, $ano) {
        $this->pagina = new LiturgiaDiariaPage($dia, $mes, $ano);
        $this->data = sprintf("%04d-%02d-%02d", $ano, $mes, $dia);

        $this->finder = $this->inicializarFinder();

        $this->titulo = $this->getTitulo($this->finder);
    }

    private function inicializarFinder() {
        $html = $this->pagina->getHTML();
        //$html = utf8_encode($html);

        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        return new DomXPath($dom);
    }

    private function getTitulo(DomXPath $finder) {
        return HTMLUtils::removeBreak($finder->query("//h2")[0]->nodeValue);
    }

    public function getLeituras() {
        $leituras = [];
        $leiturasDOM = $this->buscarLeituras($this->finder);

        foreach($leiturasDOM as $leitura)
            $leituras[] = (new LeituraBuilder($leitura))->gerar();

        return $leituras;
    }

    private function buscarLeituras(DomXPath $finder) {
        //#corpo_leituras > div
        $query = "descendant-or-self::*[@id = 'corpo_leituras']/div";

        $leituras = [];

        foreach($finder->query($query) as $node)
            $leituras[] = $node;

        return $leituras;
    }

    public function toArray() {
        $leituras = [];

        foreach ($this->getLeituras() as $leitura) {
            $array = $leitura->toArray();
            $leituras[$array["titulo"]] = $array["texto"];
        }

        return array(
            'data'=> $this->data,
            'titulo_dia'=> $this->titulo,
            'leituras'=> $leituras
        );
    }

}
