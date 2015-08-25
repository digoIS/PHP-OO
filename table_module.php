<?php
/*
 * class Produto
 * representa um produto a ser vendido
 */
final class Produto
{
	static $recordset = array(); //representa a nossa estrutra de dados
	
	/*
	 * método adicionar
	 * adiciona um produto ao registro
	 * @param $descricao = descrição do produtop
	 * @param estoque = estoque atual
	 * @param $preco_custo =- preço de custo
	 */
	public function adicionar($id, $descricao, $estoque, $preco_custo)
	{
		self::$recordset[$id]['descricao'] = $descricao;
		self::$recordset[$id]['estoque'] = $estoque;
		self::$recordset[$id]['preco_custo'] = $preco_custo;
	}
	
	/*
	 * método registraCompra
	 * registra uma compra, atualiza custo e incrementa o estoque atual do produto
	 * @param $unidades = unidades adquiridas
	 * @param $preco_custo = novo preco de custo
	 */
	public function registraCompra($id,$unidades, $preco_custo)
	{
		self::$recordset[$id]['preco_custo'] = $preco_custo;
		self::$recordset[$id]['estoque'] += $unidades;
	}
	/*
	 * método registraVenda
	 * registra uma venda e decrementa o estoque
	 * @param $unidades = unidades vendidas
	 */ 
	public function registraVenda($id, $unidades)
	{
		self::$recordset[$id]['estoque'] -= $unidades;
	}
	/*
	 * método getEstoque
	 * retorna a quantidade de estoque
	 */
	public function getEstoque($id)
	{
		return self::$recordset[$id]['estoque'];
	}
	/*
	 * método calculaPrecoVenda
	 * retorna o preço da venda, baseado em uma margem de 30% sobre o custo 
	 */
	public function calculaPrecoVenda($id)
	{
		return self::$recordset[$id]['preco_custo'] *1.3;
	}
}

//instancia o objeto Produto
$produto = new Produto;

//adiciona alguns Produtos
$produto->adicionar(1, 'Vinho', 10, 15);
$produto->adicionar(2, 'Salame', 20, 20);

//exibe os estoques atuais
echo "estoques: <br>\n";
echo $produto->getEstoque(1) . "<br>\n";
echo $produto->getEstoque(2) . "<br>\n";

//exibe os preços de venda
echo "preços de venda : <br>\n";
echo $produto->calculaPrecoVenda(1) . "<br>\n";
echo $produto->calculaPrecoVenda(2) . "<br>\n";

//vende algumas unidades
$produto->registraVenda(1, 5);
$produto->registraVenda(2, 10);

// repõe e estoque
$produto->registraCompra(1, 5, 16);
$produto->registraCompra(2, 10, 22);

//exibe os preços de vendas atuais
echo "preços de venda : <br>\n";
echo $produto->calculaPrecoVenda(1) . "<br>\n";
echo $produto->calculaPrecoVenda(2) . "<br>\n";