<?php
/**
 * classe TCombo
 * classe para construção de combo boxes
 * 
 */
class TCombo extends TField
{
	private $items; //array contendo os itens da combo

	/**
	 * meodo construtor
	 * instancia a combo box
	 * param $name = nome do campo
	 */
	public function __construct($name)
	{
		//executa o mpétodo coinstrutor da classe-pai
		parent::__construct($name);
		//cria uma tag HTML do tipo <select>
		$this->tag = new TElement('select');
		$this->tag->class = 'tfield'; //classe CSS
	}
	
	/**
	 * método addItems()
	 * adiciona items à combo box
	 * @param $items = array de itens
	 */
	public function addItems($items)
	{
		$this->items = $items;
	}
	
	/**
	 * método show()
	 * exibe o widget na tela 
	 */
	
	public function show()
	{
		//atribui as propriedades da TAG
		$this->tag->name = $this->name; //nome da TAG
		$this->tag->style = "width:{$this->size}"; //tamanho em pixels

		//cria uma tag <option> com um valor padrão
		$option = new TElement('option');
		$option->add('');
		$option->value = '0'; //valor da TAG
		// adiciona o opção a combo
		$this->tag->add($option);
		
		if($this->items)
		{
			//percorre os itens adicionados 
			foreach ($this->items as $chave => $item)
			{
				//cria uma TAG <option> para o item
				$option = new TElement('option');
				$option->value = $chave; //define o indice da opção
				$option->add($item);	//adiciona o texto da opção

				//caso seja a opçao selecionada
				if($chave == $this->value)
				{
					//seleciona o item da combo
					$option->selected = 1;
				}
				//adiciona a opção à combo
				$this->tag->add($option);
			}
		}
		
		//verifica se o campo é editavel
		if(!parent::getEditable())
		{
			//desabilita a TAG input
			$this->tag->readonly = "1";
			$this->tag->class = 'tfield_disabled'; //classe CSS
		}
		//exibe o combo
		$this->tag->show();
	}
}