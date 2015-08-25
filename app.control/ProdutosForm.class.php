<?php
/*
 * classe ProdutosForm
 * formulário de cadastro de produtos
 */
class ProdutosForm extends TPage
{
	private $form; //formulário
	/*
	 * método construtorm
	 * cria a página e o formulário de cadastro
	 */
	function __construct()
	{
		parent::__construct();
		//instancia um formulário
		$this->form = new TForm('form_produtos');
		//instancia uma tabela
		$table = new TTable;
		
		//adiciona tabela ao formulário
		$this->form->add($table);
		
		//cria os campos do formulário
		$codigo = new TEntry('id');
		$descricao = new TEntry('descricao');
		$estoque = new TEntry('estoque');
		$preco_custo = new TEntry('preco_custo');
		$preco_venda = new TEntry('preco_venda');
		$fabricante = new TCombo('id_fabricante');
		
		//carrega os fabricantes do banco de dados
		TTransaction::open('pg_loja');
		//instancia um repositório de Fabricante
		$repository = new TRepository('Fabricante');
		//carrega todos os objetos
		$collection = $repository->load(new TCriteria);
		//adiciona os objetos na COMBO
		foreach ($collection as $object)
		{
			$items[$object->id] = $object->nome;
		}
		$fabricante->addItems($items);
		TTransaction::close();
		
		//adiciona linha para o campo codigo
		$row = $table->addRow();
		$row->addCell(new TLabel('Código:'));
		$row->addCell($codigo);
		
		//adiciona linha para o campo descricao
		$row = $table->addRow();
		$row->addCell(new TLabel('Descriçao:'));
		$row->addCell($descricao);
		
		//adiciona linha para o campo estoque
		$row = $table->addRow();
		$row->addCell(new TLabel('Estoque:'));
		$row->addCell($estoque);
		
		//adiciona linha para o campo preço de custo
		$row = $table->addRow();
		$row->addCell(new TLabel('Preço de Custo:'));
		$row->addCell($preco_custo);
		
		//adiciona linha para o campo preço de venda
		$row = $table->addRow();
		$row->addCell(new TLabel('Preço de Venda:'));
		$row->addCell($preco_venda);
		
		//adiciona lonha para o campo fabricante
		$row = $table->addRow();
		$row->addCell(new TLabel('Fabricante:'));
		$row->addCell($fabricante);
		
		//cria um botão de ação para o formulário
		$button1 = new TButton('action1');
		//define a ação do botão
		$button1->setAction(new TAction(array($this, 'onSave')), 'Salvar');
		
		//adiciona uma linha para a ação do formulário
		$row = $table->addRow();
		$row->addCell('');
		$row->addCell($button1);
		
		//define quais são os campos do formulário
		$this->form->setFields(array($codigo, $descricao, $estoque, $preco_custo, $preco_venda, $fabricante, $button1));
		
		//adiciona o formulário na página
		parent::add($this->form);
	}
	
	/*
	 *método onEdit
	 *edita os dados de um registro 
	 */
	function onEdit($param)
	{
		try {
			//inicia transação com o banco 'pg_loja'
			TTransaction::open('pg_loja');
			
			//obtém o produto de acordo com o parâmetro
			$produto = new Produto($param['key']);
			
			//lança os dados do produto no formulário
			$this->form->setData($produto);
			
			//finaliza a trnasação
			TTransaction::close();
		}
		catch (Exception $e){
			//exibe a mensage de erro pela exceção
			new TMessage('error', '<b>Erro</b>' . $e->getMessage());
			//desfaz todaos alterações no banco de dados
			TTransaction::rollback();
		}
	}
	/*
	 *método onSave
	 *executado quando usuário clicar no botão salvar 
	 */
	function onSave()
	{
		try{
			//inicia transação com banco 'pg_loja'
			TTransaction::open('pg_loja');
			
			//lê os dados do formulário e instancuia um objeto Produto
			$produto = $this->form->getData('Produto');
			//armazena o objeto no banco de dados
			$produto->store();
			
			//finaliza a transação
			TTransaction::close();
			//exibe a mensagem de sucesso
			new TMessage('info', 'Dados armazenados com sucesso');
	
		}
		catch(Exception $e){
			//exibe a mensagem gerada pela exceção
			new TMessage('error', '<b>Erro</b>' . $e->getMessage());
			//desfaz todas as alterações no banco de dados
			TTransaction::rollback();
			
		}
	}	
}