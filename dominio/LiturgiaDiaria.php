<?php

class LiturgiaDiaria {
    private $pagina;

    private $data;
    private $titulo;
    private $cor;
    private $leiturasDoDia;
    private $leiturasFacultativas;

    public function __construct($dia, $mes, $ano) {
        $this->pagina = new LiturgiaDiariaPage($dia, $mes, $ano);

        $this->data = sprintf("%04d-%02d-%02d", $ano, $mes, $dia);
        $this->titulo = $this->pagina->getTitulo();
        $this->cor = $this->pagina->getCor();

        $this->inicializarLeituras();
    }

    private function inicializarLeituras() {
        $todasAsLeituras = $this->pagina->getLeituras();

        $titulosLeiturasFacultativas = $this->pagina->getTitulosLeiturasFacultativas();

        $this->leiturasDoDia = [];
        $this->leiturasFacultativas = [];

        foreach ($todasAsLeituras as $leitura)
            if ($this->isLeituraFacultativa($leitura, $titulosLeiturasFacultativas))
                $this->leiturasFacultativas[] = $leitura;
            else
                $this->leiturasDoDia[] = $leitura;
    }

    private function isLeituraFacultativa(Leitura $leitura, $titulosLeiturasFacultativas) {
        $titulo = HTMLUtils::removeBreak($leitura->getTitulo()->nodeValue);
        return in_array($titulo, $titulosLeiturasFacultativas);
    }

    public function getCor() {
        return $this->cor;
    }

    public function getLeiturasDoDia() {
        return $this->leiturasDoDia;
    }

    public function getLeiturasFacultativas() {
        return $this->leiturasFacultativas;
    }

    public function toArray() {
        $leiturasDoDia = $this->gerarArrayLeituras($this->getLeiturasDoDia());
        $leiturasFacultativas = $this->gerarArrayLeituras($this->getLeiturasFacultativas());

        return array(
            'data'=> $this->data,
            'cor'=> $this->cor,
            'titulo_dia'=> $this->titulo,
            'leiturasDoDia'=> $leiturasDoDia,
            'leiturasFacultativas'=> $leiturasFacultativas
        );
    }

    private function gerarArrayLeituras($leituras) {
        $retorno = [];

        foreach ($leituras as $leitura) {
            $array = $leitura->toArray();
            $retorno[$array["titulo"]] = $array["texto"];
        }

        return $retorno;
    }
}
