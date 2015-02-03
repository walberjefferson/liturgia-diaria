<?php
@header("Content-Type: text/html; charset=utf-8", true);

// Para corrigir os erros:
//ini_set('display_errors', 1);
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE | E_ALL ^ E_NOTICE);

include 'page/LiturgiaDiariaPage.php';
include 'builder/LeituraBuilder.php';
include 'dominio/LiturgiaDiaria.php';
include 'dominio/Leitura.php';
include 'util/HTMLUtils.php';

$ano = (int) isset($_GET['ano'])?(int) $_GET['ano']:date('Y');
$mes = (int) isset($_GET['mes'])?(int) $_GET['mes']:date('m');
$dia = (int) isset($_GET['dia'])?(int) $_GET['dia']:date('d');

$liturgia = new LiturgiaDiaria($dia, $mes, $ano);
$leituras = $liturgia->toArray();

$json = json_encode($leituras);
echo $json;

?>
