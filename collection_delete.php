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
 * cria as classes Active Record
 * para manuipular os registros das tabelas correspondentes
 * 
 */
class Turma extends TRecord
{
	const TABLENAME = 'turma';
}
class Inscricao extends TRecord
{
	const TABLENAME = 'inscricao';
}

//deleta objetos do banco de dados
try {
	// inicia transação com op banco de dados 'pg_livro'
	TTransaction::open('pg_livro');
	//define o arquivo para LOG
	TTransaction::setLogger(new TLoggerTXT('/tmp/log9.txt'));
	
	//primeiro exemplo, exclui todas turmas da tarde
	TTRansaction::log("** exclui as turmas da tarde");
	
	//instancia um critérrio de seleção turno = 'T'
	$criteria = new TCriteria();
	$criteria->add(new TFilter('turno', '=', 'T'));
	
	//instancia repositórioi de Turmas
	$repository = new TRepository('Turma');
	//retorna os bojetos que satisfazem o critério
	$turmas = $repository->load($criteria);
	//erifica se retornou alguma turma
	if ($turmas)
	{
		// percorre todas as turmas retornadas
		foreach($turmas as $turma)
		{
			// exclui a turma
			$turma->delete();
		}
		// segundo exemplo, exclui as inscrições o aluno "1"
		TTransaction::log("** exclui as inscrições do aluno '1'");
		// instancia critério de seleção de dados rf_aluno = '1'
		$criteria = new TCriteria();
		$criteria->add(new TFilter('ref_aluno', '=', 1));
		
		//instancia um repositório de Inscrição
		$repository =  new TRepository('Inscricao');
		//exclui todos os objetos que satisfaçãm este critério de seleção
		$repository->delete($criteria);
		
		echo "Registros excluíudo ccom sucesso <br>\n";
		// finaliza a transação
		TTransaction::close();
	}
} 
catch (Exception $e) 
{
	//exibe a mensagem gerada pela exceção
	echo "<b>>Erro</b>" . $e->getMessage();
	//desfaz todas as alterações no banco de dados
	TTransaction::rollback();
}