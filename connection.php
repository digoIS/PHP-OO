<?php
/*
 * função __autoload()
 * Carrega uma classe quando la é necessária
 * ou seja quando ela é instacia pela primeira vez 
 */
function __autoload($classe)
{
	if (file_exists("app.ado/{$classe}.class.php"))
	{
		include_once "app.ado/{$classe}.class.php";
	}
}

//cria instrução SELECT
$sql = new TSqlSelect;
//define o nome da entidade
$sql->setEntity('famosos');
//acrescenta colunas 'a consulta
$sql->addColumn('codigo');
$sql->addColumn('nome');
//cria cirtério de seleção de dados
$criteria = new TCriteria;
//obtém a pessoa de código 1
$criteria->add(new TFilter('codigo', '=', '1'));
//atribui o critério de deleção de dados
$sql->setCriteria($criteria);

try 
{
	//abre uma conexão com a base my_livro (mysql)
	$conn = TConnection::open('my_livro');
	
	//executa a instrução sql
	$result = $conn->query($sql->getInstruction());
	if($result)
	{
		$row = $result->fetch(PDO::FETCH_ASSOC);
		//exibe os dados resultantes
		echo $row['codigo'] . ' - ' . $row['nome'] . "<br />\n";
	}
	//fecha a conexão
	$conn = null;
}
catch (PDOException $e)
{
	//exibe a mensagem de erro
	print "ErroMYSQL: " . $e->getMessage() . "<br />";
	die(); 
	
}

try 
{
	// abre a conexão coma base pg_livro (postgres)
	$conn = Tconnection::open('pg_livro');

	//executa a instrução sql
	$result = $conn->query($sql->getInstruction());
	if($result)
	{
		$row = $result->fetch(PDO::FETCH_ASSOC);
		//exibe os dados resultantes
		echo  $row['codigo'] . ' - ' . $row['nome'] . "<br />\n";
	}
	//fecha a conexão
	$conn = null;
} 
catch (Exception $e) 
{
	//exibe a mensagem de erro
	print "ErroPGSQL: " . $e->getMessage() . "<br/>";
	die();
}