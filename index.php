<?php
@header("Content-Type: text/html; charset=utf-8", true);

// Para corrigir os erros:
//ini_set('display_errors', 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_ALL ^ E_NOTICE);

include 'page/LiturgiaDiariaPage.php';
include 'Leitura.php';

function DOMinnerHTML(DOMNode $element) { 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child)
        $innerHTML .= $element->ownerDocument->saveHTML($child);

    return $innerHTML;
} 

function removeTitle($html){
    $html = preg_replace('#<h3(.*?)>(.*?)</h3>#is', '', $html);
    $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);
    $html = preg_replace('#<div(.*?)class="text-right addthis"(.*?)>(.*?)</div>#is', '', $html);
    $html = preg_replace('#\s(id|class)="[^"]+"#', '', $html);
    $html = str_replace(array("\t", "\n","\r"), '', $html);
    $html = str_replace('  ', ' ', $html);
    return $html;
}

function removeBreak($string){
    return trim(preg_replace('/\s+/', ' ', $string));
}

function cleanHTML($html) {
    //$html  = utf8_encode($html);
    $dom = new DOMDocument();
    $dom_aux = new DOMDocument();

    @$dom->loadHTML($html);

    $finder      = new DomXPath($dom);

    foreach($finder->query("//h2") as $node) $titulo_dia = removeBreak(DOMinnerHTML($node));

    foreach($finder->query("//h3[contains(.,'1ª')]") as $node) $primeira_leit_titulo = removeBreak(DOMinnerHTML($node));
    foreach($finder->query("//h3[contains(.,'2ª')]") as $node) $segunda_leit_titulo = removeBreak(DOMinnerHTML($node));
    foreach($finder->query("//h3[contains(translate(., 'SALMO', 'salmo'), 'salmo')]") as $node) $salmo_titulo = removeBreak(DOMinnerHTML($node));
    foreach($finder->query("//h3[contains(translate(., 'EVANGELHO', 'evangelho'), 'evangelho')]") as $node) $evangelho_titulo = removeBreak(DOMinnerHTML($node));
    foreach($finder->query("//h3[contains(translate(., 'REFLEXÃO', 'reflexão'), 'reflexão')]") as $node) $reflexao_titulo = removeBreak(DOMinnerHTML($node));

    foreach($finder->query("//h3[contains(.,'1ª')]") as $node) $primeira_leit = removeTitle(DOMinnerHTML($node->parentNode));
    foreach($finder->query("//h3[contains(.,'2ª')]") as $node) $segunda_leit = removeTitle(DOMinnerHTML($node->parentNode));
    foreach($finder->query("//h3[contains(translate(., 'SALMO', 'salmo'), 'salmo')]") as $node) $salmo = removeTitle(DOMinnerHTML($node->parentNode));
    foreach($finder->query("//h3[contains(translate(., 'EVANGELHO', 'evangelho'), 'evangelho')]") as $node) $evangelho = removeTitle(DOMinnerHTML($node->parentNode));
    foreach($finder->query("//h3[contains(translate(., 'REFLEXÃO', 'reflexão'), 'reflexão')]") as $node) $reflexao = removeTitle(DOMinnerHTML($node->parentNode));
    
    $primeiraLeitura = new Leitura($primeira_leit_titulo, $primeira_leit);
    $segundaLeitura  = new Leitura($segunda_leit_titulo, $segunda_leit);
    $salmo = new Leitura($salmo_titulo, $salmo);
    $evangelho = new Leitura($evangelho_titulo, $evangelho);
    $reflexao = new Leitura($reflexao_titulo, $reflexao);

    $ano = (int) $_GET['ano']>0?(int) $_GET['ano']:date('Y');
    $mes = (int) $_GET['mes']>0?(int) $_GET['mes']:date('m');
    $dia = (int) $_GET['dia']>0?(int) $_GET['dia']:date('d');

    return array(
        'data'=> $ano.'-'.$mes.'-'.$dia,
        'titulo_dia'=> $titulo_dia,
        'primeira_leit'=>$primeiraLeitura->toArray(),
        'segunda_leit'=>$segundaLeitura->toArray(),
        'salmo'=>$salmo->toArray(),
        'evangelho'=>$evangelho->toArray(),
        'reflexao'=>$reflexao->toArray()
    );
}

$ano = (int) isset($_GET['ano'])?(int) $_GET['ano']:date('Y');
$mes = (int) isset($_GET['mes'])?(int) $_GET['mes']:date('m');
$dia = (int) isset($_GET['dia'])?(int) $_GET['dia']:date('d');

//////////////////////////////////////////

$pagina = new LiturgiaDiariaPage($dia, $mes, $ano);
$dados  = cleanHTML($pagina->getHTML());
$json =  json_encode($dados);
echo $json;
?>
