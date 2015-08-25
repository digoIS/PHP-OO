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
 * classe Aluno filha de TRecord
 * persiste um aluno no banco de dados
 */
class Aluno extends TRecord
{
	const TABLENAME = 'aluno';
}
/*
 * classe Curso filha de TRecord
 * persiste Cursi no bancco de dados
 */
class Curso extends TRecord
{
	const TABLENAME = 'curso';
}
try {
	//inicia transação com banco 'pg_livro'
	TTransaction::open('pg_livro');
	//define o arquivo para LOG
	TTransaction::setLogger(new TLoggerTXT('/tmp/log5.txt'));
	
	//aramazena esta frase no arquivo de LOG
	TTransaction::log("** Apagando da primeira forma");
	
	//carrega o objeto
	$aluno = new Aluno(1);
	//deleta o objeto
	$aluno->delete();
	
	//armazena esta frase no arquivo de LOG
	TTransaction::log("** Apagando da segunda forma");
	//instancia o modelo
	$modelo =  new Aluno;
	//delete o objeto
	$modelo->delete(2);
	
	//finaliza a transação
	TTransaction::close();
	
	echo "Exclusão realizada com sucesso <br>\n";
} 
catch (Exception $e) 
{
	//exibe a mensagem gerada pela exceção
	echo '<b>Erro</b>' . $getMessage();
	//desfaz todas as alteraçoes no banco de dados
	TTransaction::rollback();
}