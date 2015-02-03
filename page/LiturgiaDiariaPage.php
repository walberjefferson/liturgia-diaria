<?php

include 'Page.php';

class LiturgiaDiariaPage extends Page {
    public function __construct($dia, $mes, $ano) {
        $url = 'http://liturgiadiaria.cnbb.org.br/app/user/user/UserView.php?ano='.$ano.'&mes='.$mes.'&dia='.$dia;
        parent::__construct($url);
    }

    public function getLeituras($dom) {
    	$leituras = [];
    	$leiturasDOM = buscarLeituras($dom);

    	foreach($leiturasDOM as $leitura)
        	$leituras[] = (new LeituraBuilder($leitura))->gerar();

    	return $leituras;
	}
}
