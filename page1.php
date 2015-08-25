<?php
include_once 'app.widgets/TElement.class.php';
include_once 'app.widgets/TPage.class.php';

function ola_mundo($param)
{
	echo 'Olá meu amigo ' . $param['nome'] . 'usando a função '. $param['method'];
}

$pagina = new TPage;
$pagina->show();
