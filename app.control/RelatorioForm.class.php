<?php
/*
 * class RelatorioForm
 * relatorio de vendas por período
 */
class RelatorioForm extends TPage
{
	private $form; //formulário de entrada

	/*
	 * método construtor
	 * cria a página e o formulário de parâmetros
	 */
	public function __construct()
	{
		parent::__construct();
		//instancia um formuláio
		$this->form = new TForm('form_relat_vendas');
		
		//instancia uma tabela
		$table = new TTable();
		
		//adiciona a tabela ao formulário
		$this->form->add($table);
		
		//cria os campos do formulário
		$data_ini = new TEntry('data_ini');
		$data_fim = new TEntry('data_fim');
		//define os tamanhos
		$data_fim->setSize(100);
		$data_ini->setSize(100);
		
		//adiciona uma linha para o capo data inicial
		$row=$table->addRow();
		$row->addCell(new TLabel('Data Inicial:'));
		$row->addCell($data_ini);
		
		//adiciona uma linha para o campo data_final
		$row=$table->addRow();
		$row->addCell(new TLabel('Data Final:'));
		$row->addCell($data_fim);
		
		//cria um botão de ação
		$gera_button = new TButton('gera');
		//define a ação do button
		$gera_button->setAction(new TAction(array($this, 'onGera')), 'Gerar Relatório');
		
		//adiciona uma linha paraa ação do formulário
		$row=$table->addRow();
		$row->addCell($gera_button);
		
		//define quaais são os campos do formulário
		$this->form->setFields(array($data_ini, $data_fim, $gera_button));
		
		//adiciona o formulário à página
		parent::add($this->form);
	}
	
	/*
	 * métoo onGera()
	 * gera o relatório, baseaddo nos parâmetos do formulário
	 */
	function onGera()
	{
		//obtém os dados do formulário 
		$dados = $this->form->getData();
		//joga os dados de volta ao formulário
		$this->form->setData($dados);
		
		//lê os campos do formulário, converte para o padrão americano
		$data_ini = $this->conv_data_to_us($dados->data_ini);
		$data_fim = $this->conv_data_to_us($dados->data_fim);
		
		//instancia uma nova tabela
		$table = new TTable();
		$table->border = 1;
		$table->width = '80%';
		$table->style = 'border-collapse:collapse';
		
		//adiciona uma linha para o cabecalho do relatório
		$row = $table->addRow();
		$row->bgColor = '#a0a0a0';
		//adiciona as células ao cabeçalho
		$cell = $row->addCell('Data');
		$cell = $row->addCell('Cliente/Produtos');
		$cell = $row->addCell('Qtde');
		$cell->align = 'right';
		$cell = $row->addCell('Preço');
		$cell->align = 'right';
		
		try
		{
			//inicia transação com o banco 'pg_loja'
			TTransaction::open('pg_loja');
			
			//instancia um repositório da classe Venda
			$repositorio =  new TRepository('Venda');
			//cria um crierio de seleção por itervalo de datas
			$criterio = new TCriteria();
			$criterio->add(new TFilter('data_venda', '>=', $data_ini));
			$criterio->add(new TFilter('data_venda', '<=', $data_fim));
			$criterio->setProperty('order', 'data_venda');
			
			//lê todas vendas que satisfazem o criterio
			
			$vendas = $repositorio->load($criterio);
			//verifica se retornou algum objeto
			if ($vendas)
			{
				//percorre as vendas
				foreach($vendas as $venda)
				{
					//adiciona uma linha a tabela e define suas propriedades
					$row = $table->addRow();
					$row->bg_color = "#e0e0e0";
					//adiciona as células para a data da venda  e dados
					$cell = $row->addCell($this->conv_data_to_br($venda->data_venda));
					$cell = $row->addCell($venda->id_cliente . ' : ' . $venda->cliente->nome);
					$cell->colspan = 3;
					
					//verifica se a venda possui itens 
					if($venda->itens)
					{
						$sub_total = 0;
						$total_qtde=0;
						//percorre os itens da venda
						foreach ($venda->itens as $item)
						{
							// adiciona uma linhapara cada item da data
							$row = $table->addRow();
							//adiciona as células com os dados do item
							$cell = $row->addCell('');
							$cell = $row->addCell($item->id_produto . ' : ' . $item->descricao);
							$cell = $row->addCell($item->quantidade);
							$cell->align = 'right';
							$cell = $row->addCell(number_format($item->preco_venda,2,',','.'));
							$cell->align = 'right';
							//acumula os totais de valor e quantidade
							$sub_total += $item->quantidade * $item->preco_venda;
							$total_qtde += $item->quantidade; 
						}
						//adiciona uma linha para os totais da venda
						$row = $table->addRow();
						$cell = $row->addCell('');
						$cell = $row->addCell('<b>Sub-Total</b>');
						$cell = $row->addCell('<b>'.$total_qtde.'</b>');
						$cell->align = 'right';
						$cell = $row->addCell('<b>'.number_format($sub_total,2,',','.').'</b>');
						$cell->align = 'right';
					}
				}
			}
			//finaliza a transação
			TTransaction::close();
		}
		catch (Exception $e)
		{
			//exibe a mensagem gerada pela exceção
			new TMessage('error', $e->getMessage());
			//desfaz todas as alterações do banco de dados
			TTransaction::rollback();
		}
		//adiciona a tabela a página
		parent::add($table);
	}
	/*
	 * método conv_data_to_us()
	 * Converte uma data para o formato yyyy-mm-dd
	 * @param $data = data no formato dd/mm/yyyy
	 */
	function conv_data_to_us($data)
	{
		$dia = substr($data, 0,2);
		$mes = substr($data, 3,2);
		$ano = substr($data, 6,4);
		return "{$ano}-{$mes}-{$dia}";
	}
	/*
	 * metodo conv_data_to_br()
	 * Converte uma data para o formato dd/mm/yyyy
	 * @param $data  = data no formato yyyy-mm-dd
	 */
	function conv_data_to_br($data)
	{
		//captura as partes da data
		$ano = substr($data,0,4);
		$mes = substr($data,5,2);
		$dia = substr($data,8,4);
		//retorna a data resultante
		return "{$dia}/{$mes}/{$ano}";
	}
}