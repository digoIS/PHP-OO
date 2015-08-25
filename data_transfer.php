<?php
/*
 * classe ProdutoGateway
 * implementa Table Data Gateway
 */
class ProdutoGateway
{
	/*
	 * método insert()
	 * insere dados na tabela de Produtos
	 * @param $id			= ID do produto
	 * @param $descricao	= descrição do produto
	 * @param $estoque		= estoque atual
	 * @param $preco_custo	= preço de custo
	 */
	function insert(Produto $object)
	{
		//cria instrução SQL de insert
		$sql = "INSERT INTO Produtos (id, descricao, estoque, preco_custo)" .
				"VALUES ('$object->id', '$object->descricao'," . 
				"'$object->estoque', '$object->preco_custo')";
		
		//instancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa instrução SQL
		$conn->exec($sql);
		unset($conn); 
	}
	
	/*
	 * método update()
	 * altera os dados na tabela de Produtos
	 * @param $id			= ID do produto
	 * @param $descricao	= descrição do produto
	 * @param $estoque		= estoque atual
	 * @param $preco_custo	= preço de custo
	 */
	function update(Produto $object)
	{
		//cria instrução SQL de UPDATE
		$sql = "UPDATE produtos set descricao = '$object->descricao'," .
		" estoque = '$object->estoque', preco_custo = '$object->preco_custo' ".
		" WHERE id = '$object->id'";
		
		//instancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa a instrução SQL
		$conn->exec($sql);
		unset($conn);
	}
	/*
	 * método delete()
	 * deleta um registro na tabela de Produtos
	 * @param $id = ID do produto
	 */
	function delete($id)
	{
		//cria a instrução SQL de DELETE
		$sql = "DELETE FROM produtos where id = '$id'";
		//instancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa instrução SQL
		$conn->exec($sql);
		unset($conn); 
	}
	/*
	 * método getObject
	 * busca um registro da tabela de produtos
	 * @param $id = ID do produto
	 */
	function getObject($id)
	{
		//cria instrução SQL de SELECT
		$sql = "SELECT * FROM produtos where id='$id'";
		//instancia objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa a consulta SQL
		$result = $conn->query($sql);
		$data = $result->fetch(PDO::FETCH_ASSOC);
		unset($conn);
		return $data;
	}
	/* método get Objects
	 * lista todos os registros da tablea de produtos
	 */
	function getObjects()
	{
		//cria a instrução SQL de SELECT
		$sql = "SELECT * FROM produtos";
		
		//instancia objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		// executa a consulta SQL
		$result = $conn->query($sql);
		$data = $result->fetchAll(PDO::FETCH_ASSOC);
		unset($conn);
		return $data;
	}
}

class Produto
{
	public $id;
	public $descricao;	
	public $estoque;
	public $preco_custo;
}

//instancia um objeto ProdutoGateway
$gateway = new ProdutoGateway();

$vinho = new Produto();
$vinho->id 			= 4;
$vinho->descricao 	= 'Vinho';
$vinho->estoque		= 10;
$vinho->preco_custa	= 15;

//insere o objeto no banco de dados
$gateway->insert($vinho);

//exibe op objeto de código 4
print_r($gateway->getObject(4));

$vinho->descricao = 'Vinho Cabernet';
//atualiza o objeto no banco de dados
$gateway->update($vinho);

//exibe op objeto de código 4
print_r($gateway->getObject(4));


//exibe novamente os registros
echo "Lista de Produtos<br/>\n";
print_r($gateway->getObjects());