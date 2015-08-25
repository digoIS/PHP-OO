<?php
/**
 * classe TTableRow
 * responsável pela exibição de uma tabela
 */
class TTableRow extends TElement
{
	/**
	 * método cconstrutor
	 * instancia uma nova linha
	 */
	public function __construct()
	{
		parent::__construct('tr');
	}
	
	/**
	 * método addCell
	 * agrega um novo objeto célula (TTableCell) à linha
	 * @param $value = conteúdo da célula
	 */
	public function addCell($value)
	{
		//instancia objeto célula
		$cell = new TTableCell($value);
		parent::add($cell);
		//retorna o objeto instanciado
		return $cell;
	}
}