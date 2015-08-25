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
 * classe Aluno, filha de TRecord
 * persiste um Aluno no Bancco de Dados
 *  
 */
class Aluno extends TRecord
{
	const TABLENAME = 'aluno';
}
/*
 * classe Curso, filha de TRecord
 * persiste um Curso no banco de dados
 *  
 */
class Curso extends TRecord
{
	const TABLENAME = 'curso';
}

//instancia objeto Alunio
$fabio = new Aluno;
//define algumas propriedades
$fabio->nome		=	'Fábio Locatelli';
$fabio->endereco	=	'Rua Merlin';
$fabio->telefone	=	'(51) 2222-1111';
$fabio->cidade		=	'Lajeado';

//clona o objeto $fabio
$julia = clone $fabio;
//altera algumas propriedades
$julia->nome		=	'Júlia Haubert';
$julia->telefone	=	'(51) 2222-2222';
try {
	//inicia transação com banco 'pg_livro'
	TTransaction::open('pg_livro');
	//define o arquivo LOG
	TTransaction::setLogger(new TLoggerTXT('/tmp/log4.txt'));
	
	//armazena o objeto $fabio
	TTransaction::log("** persistindo o aluno \$fabio");
	$fabio->store();
	//armazena o objeto $julia
	TTRansaction::log("** persistindo o aluno \$julia");
	$julia->store();
	
	//finaliza a transação
	
	TTransaction::close();
	
	echo "Clonagem realizada com sucesso <br>";
} catch (Exception $e) 
{
	// exibe a mensagem gerada pela exceção
	echo "<b><ERRO</b>" . $e->getMessage();
	//desfaz toas as alterações no banco de dados
	TTransaction::rollback();
} 