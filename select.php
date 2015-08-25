<?php
/*
 * função __autoload()
 * Carrega uma classse quando ela é necessária, ou seja, quando ela é instancia pela primeira vez 
 */

function  __autoload($classe)
{
	if (file_exists("app.ado/{$classe}.class.php"))
	{
		include_once "app.ado/{$classe}.class.php";
	}
}

//cira um critério de selção de dados
$criteria = new TCriteria;
$criteria->add(new TFilter('nome', 'like', 'maria%'));
$criteria->add(new TFilter('cidade', 'like', 'Porto%'));


//define o intervalo da consulta
$criteria->setProperty('offset', 0);
$criteria->setProperty('limit', 10);
//define o ordenamento da consulta
$criteria->setProperty('order', 'nome');

//cria instrução de SELECT
$sql = new TSqlSelect;
//define o nome da entidade
$sql->setEntity('aluno');
//acrescenta colunas a consulta
$sql->addColumn('nome');
$sql->addColumn('fone');
//define o critério de seleção de dados
$sql->setCriteria($criteria);
//processa a instrução SQL
echo $sql->getInstruction();
echo "<br />\n";