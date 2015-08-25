<?php
//inserir função __autoload() para carregar as classes ....
function __autoload($classe)
{
	if(file_exists("app.ado/{$classe}.class.php"))
	{
		include_once "app.ado/{$classe}.class.php";
	}
}
/*
 * cria as classes Active REcord
 * para manipular os registros das tabelas correpondentes
 *
 */

class Aluno extends TRecord
{
	const TABLENAME = 'aluno'; 
}
class Turma extends TRecord
{
	const TABLENAME = 'turma';
}
//conta os objetos do banco de dados
try {
	//inicia transação com banco 'pg_livro'
	TTransaction::open('pg_livro');
	//define o arquivo para LOG
	TTransaction::setLogger(new TLoggerTXT('/tmp/log8.tx'));
	
	//primeiro exemplo, conta todos alunos de Porto Alegre 
	TTransaction::log("** Conta Alunos de Porto Alegre");
	
	// instancia um critério de seleção
	$criteria = new TCriteria();
	$criteria->add(new TFilter('cidade', '=', 'Porto Alegre'));
	
	//instancia ium repositório de Alunos
	$repository = new TRepository('Aluno');
	//obté um total de alunos que satisfazem o critério
	$count = $repository->count($criteria);
	
	//exibe o total na tela 
	echo "Total de alunos de Porto Alegre: {$count} <br>\n";
	
	//segundo exemplo, Contar todas as turms com aula na sala 
	//"100"  no turno da Tarde OU na "200" pelo turno da manha.
	
	TTransaction::log("** Conta Turmas");
	
	//instancia um critério de seleção
	//sala "100" e turno T (tarde)
	
	$criteria1 = new TCriteria;
	$criteria1->add(new TFilter('sala', '=', '100'));
	$criteria1->add(new TFilter('turno', '=', 'T'));
	
	//instacia um critério de seleção
	$criteria2 = new TCriteria();
	$criteria2->add(new TFilter('sala', '=', '200'));
	$criteria2->add(new TFilter('turno', '=', 'M'));

	//instancia um critério de seleção
	//com OU para juntar os critérios antriores
	$criteria =  new TCriteria();
	$criteria->add($criteria1, TExpression::OR_OPERATOR);
	$criteria->add($criteria2, TExpression::OR_OPERATOR);
	
	//instancia um repositório de Turmas
	$repository = new TRepository('Turma');
	// retorna quantos objetos satisfazem o critério
	$count = $repository->count($criteria);
	echo "Total de turmas: {$count} <br>\n";
	
	//finaliza a transação
	TTransaction::close();
} 
catch (Exception $e) 
{
	// exibe a mensagem gerada pela exceção
	echo '<b>Erro</b>' . $e->getMessage();
	// desfaz todas alterações no banco de dados
	TTransaction::rollback();
}
