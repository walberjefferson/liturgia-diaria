<?php

include 'page/LiturgiaDiariaPage.php';
include 'builder/LeituraBuilder.php';
include 'dominio/LiturgiaDiaria.php';
include 'dominio/Leitura.php';
include 'dominio/Leituras.php';
include 'util/HTMLUtils.php';

$ano = (int)isset($_GET['ano']) ? (int)$_GET['ano'] : date('Y');
$mes = (int)isset($_GET['mes']) ? (int)$_GET['mes'] : date('m');
$dia = (int)isset($_GET['dia']) ? (int)$_GET['dia'] : date('d');

$liturgia = new LiturgiaDiaria($dia, $mes, $ano);
$leituras = $liturgia->toArray();

$leiturasDoDia = new Leituras($leituras);


?>
<!doctype html>
<html lang="pr_br">
<head>
    <meta charset="UTF-8">
    <title>Liturgia do Dia</title>
    <link href="http://liturgiadiaria.cnbb.org.br/app/arquivos/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="http://liturgiadiaria.cnbb.org.br/app/arquivos/bootstrap/css/docs.min.css" rel="stylesheet">
    <link href="http://liturgiadiaria.cnbb.org.br/app/arquivos/css/liturgia.css" rel="stylesheet">
    <link href="http://liturgiadiaria.cnbb.org.br/app/arquivos/tinymce/css/word.css" rel="stylesheet">
    <link href="http://liturgiadiaria.cnbb.org.br/app/arquivos/css/interface_calendario_user.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <div class="bs-callout bs-callout-info">
        <h2><?= $leituras['titulo_dia']; ?></h2>
        <p><strong><?= date('d/m/Y',  strtotime($leituras['data'])) ; ?></strong></p>
        <p class="pull-left"></p>
        <p class="pull-right">
            <em><?= $leituras['cor']; ?></em>
        </p>


    </div>
</div>
<div class="container">
    <?php for ($i = 0; $i < 4; $i++){
        echo '<h3 class="title-leitura">' . $leiturasDoDia->titulo($i) . '</h3>';
        echo $leiturasDoDia->texto($i);
        echo '<br><br>';
    }
    ?>

</div>
</body>
</html>

