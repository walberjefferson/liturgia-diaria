<?php

include 'Page.php';

class LiturgiaDiariaPage extends Page {
    private $finder;

    public function __construct($dia, $mes, $ano) {
        $url = 'http://liturgiadiaria.cnbb.org.br/app/user/user/UserView.php?ano='.$ano.'&mes='.$mes.'&dia='.$dia;
        parent::__construct($url);

        $this->finder = $this->inicializarFinder();
    }

    private function inicializarFinder() {
        $html = $this->getHTML();
        //$html = utf8_encode($html);

        $dom = new DOMDocument();
        @$dom->loadHTML($html);

        return new DomXPath($dom);
    }

    public function getTitulo() {
        return HTMLUtils::removeBreak($this->finder->query("//h2")->item(0)->nodeValue);
    }

    public function getCor() {
        //.container em
        $query = "descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' container ')]/descendant::em";
        return HTMLUtils::removeBreak($this->finder->query($query)->item(0)->nodeValue);
    }

    public function getLeituras() {
        //#corpo_leituras > div
        $query = "descendant-or-self::*[@id = 'corpo_leituras']/div";

        $leituras = [];
        $leiturasDOM = $this->buscarLeituras($query);

        foreach($leiturasDOM as $leitura)
            $leituras[] = (new LeituraBuilder($leitura))->gerar();

        return $leituras;
    }

    private function buscarLeituras($query) {
        $leituras = [];

        foreach($this->finder->query($query) as $node)
            $leituras[] = $node;

        return $leituras;
    }

    public function getTitulosLeiturasFacultativas() {
        //.link_leituras .list-group-item
        $query = "descendant-or-self::*[contains(concat(' ', normalize-space(@class), ' '), ' link_leituras ')]/descendant::*[contains(concat(' ', normalize-space(@class), ' '), ' list-group-item ')]";

        $leiturasFacultativas = [];

        foreach ($this->finder->query($query) as $leitura)
            $leiturasFacultativas[] = HTMLUtils::removeBreak($leitura->nodeValue);

        return $leiturasFacultativas;
    }
}
