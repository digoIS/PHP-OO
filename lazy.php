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
 * clasee Inscricao, filha de TRecord
 * persiste uma Inscricao no banco de dados 
 */
class Inscricao extends TRecord
{
	const TABLENAME = 'inscricao';
	/*
	 * funcção get_aluno()
	 * executa sempre que for acessada a propriedade "aluno"
	 * 
	 */
	function get_aluno()
	{
		// instancia Aluno, carrega
		//na memória o aluno de código $this->ref_aluno
		$aluno = new Aluno($this->ref_aluno);
		
		//retorna o objeto instanciado
		return $aluno;
	}
}	
/*
 * classe Aluno, filha de TRecord
 * persiste um Aluno no banco de dados
 */
class Aluno extends TRecord
{
	const TABLENAME = 'aluno';

	/*
	 * método get_inscricoes()
	* executado sempre se for acessada a propriedade "inscricoes"
	*/
	function get_inscricoes()
	{
		//cria um critério de seleção
		$criteria = new TCriteria;
		//filtra por codigo do aluno
		$criteria->add(new TFilter('ref_aluno', '=', $this->id));
	
		//instancia repositório de Inscrições
		$repository = new TRepository('Inscricao');
		//retorna todas inscrições que satisfazem o critério
		return $repository->load($criteria);
	}
}

try {
	// inicia transação com o banco 'pg_livro'
	TTransaction::open('pg_livro');
	//define o arquivo de LOG
	TTransaction::setLogger(new TLoggerTXT('/tmp/log11.txt'));
	
	//armazena esta frase no arquivo de LOG
	TTransaction::log("** obtendo o aluno de uma inscrição");
	
	//instancia a Inscrição cujo o ID é "2"
	$inscricao = new Inscricao(2);
	//exibe os dados relacionados de aluno (associção)
	echo "Dados da inscrição<br>\n";
	echo "==================<br>\n";
	echo 'Nome 		: ' . $inscricao->aluno->nome	. "<br>\n";
	echo 'Endereco	: ' . $inscricao->aluno->endereco . "<br>\n";
	echo 'Cidade	: ' . $inscricao->aluno->cidade . "<br>\n";

	//armazena eta frase no arquivo de LOG
	TTransaction::log("** obtendo as inscrições de um aluno");
	
	//instancia o Aluno cujo ID é "2"
	$aluno = new Aluno(2);
	
	echo "<br>\n";
	echo "Inscrições do Aluno<br>\n";
	echo "===================<br>\n";
	
	//exibe os dados relacionados de inscrições (agreções)
	foreach ($aluno->inscricoes as $inscricao)
	{
		echo '	ID		: '. $inscricao->id;
		echo '	Turma	: '. $inscricao->ref_turma;
		echo '	Nota	: '. $inscricao->nota;
		echo '	Freq. 	: '. $inscricao->frequencia;
		echo "<br>\n";	
	}
	
	//finaliza a transação
	TTransaction::close();
} 
catch (Exception $e) 
{
	//exibe a mensagem gerada pela Exceção
	echo '<b>Erro</b>' . $e->getMessage();
	//desfaz todas as alteraçẽos no banco de dados
	TTransaction::rollback();
}
	
	