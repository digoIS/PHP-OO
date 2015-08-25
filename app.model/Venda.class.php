<?php
/*
 * classe Venda
 * Active REcord para tabela venda
 */
class Venda extends TRecord
{
	const TABLENAME = 'venda';
	private $itens; //array de objetos do tipo Item

	/*
	 * função addItem()
	 * adiciona um item (produto) a venda
	 */
	public function addItem(Item $item)
	{
		$this->itens[] = $item;
	}
	
	/*
	 * função store()
	 * armazena a venda e seus items no banco de dados
	 */
	public function store()
	{
		//armazena a venda
		parent::store()	;
		
		//percorre os itens da venda
		foreach($this->itens as $item)
		{
			//armazena o item
			$item->store();
		}
	}

	/*
	 * função get_itens()
	 * retorna os itens da venda
	 */
	public function get_itens()
	{
		//instancia um repositório do item
		$repositorio = new TRepository('Item');
		//define o critério de seleção
		$criterio = new TCriteria();
		$criterio->add(new TFilter('id_venda', '=', $this->id));
		//carrega a coleção de itens
		$this->itens = $repositorio->load($criterio);
		//retorna os itens
		return $this->itens;
	}
	/*
	 * método get_cliente()
	 * retorna o objeto cliente vinculado á venda
	 */
	function get_cliente()
	{
		//instancia Cliente, carrega
		//na memoria o cliente de código $this->id_cliente
		$cliente = new Cliente($this->id_cliente);
		
		//retorna o objeto instanciado
		return $cliente;
	}
	
}