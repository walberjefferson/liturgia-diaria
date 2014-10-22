<?php
@header("Content-Type: text/html; charset=utf-8",true);

$year = (int) $_GET['year']>0?(int) $_GET['year']:date('Y');
$month = (int) $_GET['month']>0?(int) $_GET['month']:date('m');
$day = (int) $_GET['day']>0?(int) $_GET['day']:date('d');

function DOMinnerHTML(DOMNode $element) 
{ 
    $innerHTML = ""; 
    $children  = $element->childNodes;

    foreach ($children as $child) 
    { 
      $innerHTML .= $element->ownerDocument->saveHTML($child);
    }

    return $innerHTML; 
} 

function getFileFromURL($url) {
	if($url!='') {
		if(function_exists('curl_init')) {
			$ch = curl_init();
			$timeout = 0;
			curl_setopt ($ch, CURLOPT_URL, $url);
			curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
			$dados = curl_exec($ch);
			curl_close($ch);
		} else {
			$dados = @file_get_contents($url);
		}
		
		return $dados;
	}
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

function cleanHTML($html){
	
	//$html  = utf8_encode($html);
	$dom = new DOMDocument();
	$dom_aux = new DOMDocument();

	@$dom->loadHTML($html);

	$script = $dom->getElementsByTagName('script');
	$link 	= $dom->getElementsByTagName('link');
	$meta 	= $dom->getElementsByTagName('meta');
	$img 		= $dom->getElementsByTagName('img');
	$nav 		= $dom->getElementsByTagName('nav');


	$finder 			= new DomXPath($dom);
	$classname		=	"sidebar-module calendario";
	$sidebar 			= $finder->query("//*[contains(@class, '$classname')]");
	$blog_footer 	= $finder->query("//*[contains(@class, 'blog-footer')]");
	$addthis 			= $finder->query("//*[contains(@class, 'addthis')]");
	
	//$primeira_leit 	= $finder->query("//h3[contains(., 'Primeira')]");



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
	
	
	
	$year = (int) $_GET['year']>0?(int) $_GET['year']:date('Y');
	$month = (int) $_GET['month']>0?(int) $_GET['month']:date('m');
	$day = (int) $_GET['day']>0?(int) $_GET['day']:date('d');

	return array(
		'data'=> $year.'-'.$month.'-'.$day,
		'titulo_dia'=> $titulo_dia,
		'primeira_leit'=>array('titulo'=> $primeira_leit_titulo, 'texto'=> $primeira_leit),
		'segunda_leit'=>array('titulo'=> $segunda_leit_titulo, 'texto'=> $segunda_leit),
		'salmo'=>array('titulo'=> $salmo_titulo, 'texto'=> $salmo),
		'evangelho'=>array('titulo'=> $evangelho_titulo, 'texto'=> $evangelho),
		'reflexao'=>array('titulo'=> $reflexao_titulo, 'texto'=> $reflexao)
	);
}




$dados = getFileFromUrl('http://liturgiadayria.cnbb.org.br/app/user/user/UserView.php?year='.$year.'&month='.$month.'&day='.$day);
$dados = cleanHTML($dados);
$json =  json_encode($dados);
echo $json;	

?>