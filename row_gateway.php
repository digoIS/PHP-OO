<?php
/*
 * classe Produto
 * implementa Active Record
 * 
 */
class Produto
{
	private $data;
	
	function __get($prop)
	{
		return $this->data[$prop];
	}
	
	function __set($prop, $value)
	{
		$this->data[$prop] = $value;
	}
	
	/*
	 * método insert
	 * armazena o objeto na tabela produtos
	 */
	function insert()
	{
		//cria instrução SQL de insert
		$sql = "INSERT INTO Produtos (id, descricao, estoque, preco_custo)" .
				"VALUES ('{$this->id}','{$this->descricao}',".
				"		'{$this->estoque}', '{$this->preco_custo}')";
		echo $sql . "<br />\n";
		//instancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa a instrução SQL
		$conn->exec($sql);
		unset($conn);			
	}
	/*
	 * método update
	 * altera os dados do objeto na tabela de Produtos
	 */
	function update()
	{
		// cria instrução SQL de UPDATE
		$sql = "UPDATE produtos set ".
				" descricao		= '{$this->descricao}'," .
				" estoque		= '{$this->estoque}'," .
				"preco_custo 	= '{$this->preco_custo}'".
				"WHERE id = '{$this->id}'";
		echo $sql . "<br />\n";
		//inancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa a instrução SQL
		$conn->exec($sql);
		unset($conn); 
	}
	/*
	 * método delete
	 * deleta o objeto da tabela de Produtos
	 * 
	 */
	function delete()
	{
		//cria a instrção SQL de DELETE
		$sql = "DELETE FROM produtos where id = '{$this->id}'";
		echo $sql . "<br />\n";
		// instancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//executa a instrução SQL
		$conn->exec($sql);
		unset($conn);
	}
	/*
	 * método getObject
	 * carrega um objeto a partir da tabela de produtos
	 */
	function getObject($id)
	{
		// cria um instrução SELECT
		$sql = "SELECT * FROM produtos where id ='{$id}'";
		echo  $sql . "<br />\n";

		//instancia o objeto PDO
		$conn = new PDO('sqlite:produtos.db');
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
		//exeuta consulta SQL
		$result = $conn->query($sql);
		$this->data = $result->fetch(PDO::FETCH_ASSOC);
		unset($conn);
	}
	/*
	 * método registraCompra
	 * registra uma compra, atualiza custo e incrementa o estoque atual 
	 * @param $unidades = unidades adquiridas
	 * @param $preco_custo = novo preço de custo 
	 */
	public function registraCompra($unidades, $preco_custo)
	{
		$this->preco_custo = $preco_custo;
		$this->estoque += $unidades;
	}
	/*
	 * método registraVenda
	 * registra uma venda e decrementa o estoque
	 * @param $unidades  = unidades vendidas
	 */
	public function registraVenda($unidades)
	{
		$this->estoque -= $unidades;
	}
	/*
	 * método calculaPrecoVenda
	 * retorna o preco de venda, baseado em uma margem de 30% sobre o custo 
	 */
	public function calculaPrecoVenda()
	{
		return $this->preco_custo * 1.3;
	}
	
}
//instancia objeto Produto
$vinho = new Produto;
$vinho->id = 7;
$vinho->descricao = 'Vinho Cabernet';
$vinho->estoque = 10;
$vinho->preco_custo = 10;
$vinho->insert();

$vinho->registraVenda(5);
echo 'estoque:		' . $vinho->estoque . "<br>\n";
echo 'preco_custo:  ' . $vinho->preco_custo . "<br />\n";
echo 'preco_venda:  ' . $vinho->calculaPrecoVenda() . "<br />\n";

$vinho->registraCompra(10, 20);
$vinho->update();
echo 'estoque: 		' . $vinho->estoque . "<br />\n";
echo 'preco_custo:  ' . $vinho->preco_custo . "<br />\n";
echo 'preco_venda:	' .	$vinho->calculaPrecoVenda() . "<br />\n";	 