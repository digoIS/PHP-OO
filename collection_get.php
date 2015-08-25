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
 * para maniupular os registos das tabelas correspondentes
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
class Inscricao extends TRecord
{
	const TABLENAME = 'inscricao';
}
//obtém os objetos do banco de dados
try {
	//inicia transaão com banco 'pg_livro'
	TTransaction::open('pg_livro');
	//define o arquivo para LOG
	TTransaction::setLogger(new TLoggerTXT('/tmp/log6.txt'));
	
	//primeiro exeemplo, lista todas turmas em andamento no turno Tarde #
	
	//cria um critério de seleeção
	$criteria = new TCriteria();
	// filtra por turnio e encerrada
	$criteria->add(new TFilter('turno', '=', 'T'));
	$criteria->add(new TFilter('encerrada', '=', FALSE));

	//instancia uum repositório para Turma
	$repositorio = new TRepository('Turma');
	//retorna todos objetos que satisfazem o critério
	$turmas = $repositorio->load($criteria);
	//verifica se retornou alguma turma
	if ($turmas)
	{
		echo  "Turmas retornadas <br />\n";
		echo "================== <br />\n";
		//percorre todas turmas retornadas
		foreach ($turmas as $turma)
		{
			echo ' 	ID		: ' . $turma->id;
			echo '	Dia		: ' . $turma->dia_semana;
			echo '	Sala	: ' . $turma->sala;
			echo '	Turno	: ' . $turma->turno;
			echo '	Professor	: ' . $turma->professor;
			echo "<br>\n";
		}
	}
	//segundo exemplo, lista todos aprovados da turma "1" #

	//instancia um critério de seleção
	$criteria =  new TCriteria();
	$criteria->add(new TFilter('nota', '>=', 7));
	$criteria->add(new TFilter('frequencia', '>=', 75));
	$criteria->add(new TFilter('ref_turma', '=', 1));
	$criteria->add(new TFilter('cancelada', '=', FALSE));
	
	//instancia um repositório pára Inscrição
	$repository = new TRepository('Inscricao');
	//retorna os objetos que satisfazem o critério
	$inscricoes = $repository->load($criteria);
	//verifica se retornou alguma inscricao
	if ($inscricoes)
	{
		echo "Inscricoes retornadas <br>\n";
		echo "====================== <br>\n";
		// percorre todas inscrições retornadas
		foreach ($inscricoes as $inscricao)
		{
			echo '	ID		: ' . $inscricao->id;
			echo '	Aluno	: ' . $inscricao->ref_aluno;
			
			//obtém o aluno relacionado à inscrição
			$aluno = new Aluno($inscricao->ref_aluno);
			echo '	Nome	: ' . $aluno->nome;
			echo '	Rua		: ' . $aluno->nome;
			echo '<br>\n';
		}
	}
	// finaliza a transação
	TTransaction::close();
	
} 
catch (Exception $e) 
{
	// exibe a mensagem gerada pela exceção
	echo "<b>Erro</b>" . $e->getMessage();
	// desfaz todas alterações no banco de dados
	TTransaction::rollback();
}	