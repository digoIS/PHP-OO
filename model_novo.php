<?php
/*
 * função  __autoload()
 * carrega uma classe qunado ela é necessária 
 * ou seja , quando ela é instancia pela primeira vez
 * 
 */
function __autoload($classe)
{
	if(file_exists("app.ado/{$classe}.class.php"))
	{
		include_once "app.ado/{$classe}.class.php";
	}
}

/*
 * classe Aluna, fila de TRecord
 * persiste um Aluno no banco e dados
 */
class Aluno extends TRecord
{
	const TABLENAME = 'aluno';
}
/*
 * classe Curso, filha de TRecord
 * persiste um Curso no banco de dados
 */
class Curso extends TRecord
{
	const TABLENAME = 'curso';
}
//insere novos objetos no banco de dados
try 
{
//inicia transação com 'pg_livro'
TTransaction::open('pg_livro');
//define o arquyivo para LOG
TTransaction::setLogger(new TLoggerTXT('/tmp/log1.txt'));	

//armazena esta frase no arquivo de LOG
TTransaction::log("** inserindo alunos");

//instancia um novo objeto aluno
$daline = new Aluno;
$daline->nome = 'Daline Dall Oglio';
$daline->endereco = 'Rua da Conceiçao';
$daline->telefone = '(51) 1111-1111';
$daline->cidade = 'Cruzeiro do Sul';
$daline->store(); //aramazena o objeto

//instancia um novo objeto aluno
$william = new Aluno;
$william->nome = 'William Scatola';
$william->endereco = 'Rua de Fátima';
$william->telefone = '(52) 1111-1111';
$william->cidade = 'Encantado';
$william->store(); //armazena o objeto

//armazena esta frase no arquivo de LOG
TTransaction::log('** inserindo cursos');
//instancia um novo objeto curso
$curso = new Curso();
$curso->descricao = 'Orientação a Objetos com PHP';
$curso->duracao = 24;
$curso->store(); //armazena o objeto

//instancia um novo objeto
$curso = new Curso();
$curso->descricao = 'Desenvolvimento em PHP-GTK';
$curso->duracao = 32;
$curso->store(); //armazena o objeto

//finaliza a transação
TTransaction::close();
echo "Registros inseridos com Sucesso <br />\n";
} 
catch (Exception $e) //em caso de exceção 
{
	//exibe a mensagem gerada pela exceção
	echo '<b>Erro</b>' . $e->getMessage();
	//desfaz todas as alterações no banco de dados
	TTransaction::rollback();
}
		