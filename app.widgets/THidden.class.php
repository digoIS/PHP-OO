<?php
/**
 * classe THidden
 * classe para a construção  de campos escondidos
 * 
 */
class THidden extends TField
{
	/**
	 * método show()
	 * exibe o widget na tela
	 */
	public function show()
	{
		$this->tag->name	= $this->name; 				//nome da TAG
		$this->tag->value 	= $this->value; 			//valor da tag
		$this->tag->type	= 'hidden'; 				//tipo de input
		$this->tag->style	= "width:{$this->size}";	//tamanho em pixels
		
		//exibe a tag
		$this->tag->show();
		
	}
}
