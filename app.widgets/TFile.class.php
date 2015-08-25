<?php
/**
 * classe TFile
 * classe para a contrução de botões de seleção de arquivos
 */
class TFile extends TField
{
	/**
	 * métdo show()
	 * exibe o widget na tela
	 */
	public function show()
	{
		//atribui as propriedades da TAG
		$this->tag->name	= $this->name;	//nome da tag
		$this->tag->value	= $this->value;	//valor da tag
		$this->tag->type	= 'file'; 		//tipo de input
		//se o campo não é editável
		if (!parent::getEditable())
		{
			//desabilit a a TAG input
			$this->tag->readonly = "1";
			$this->tag->class = 'tfield_disabled'; // classe CSS
		}

		//exibe a TAG
		$this->tag->show();
	}
}