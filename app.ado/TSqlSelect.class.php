<?php
/*
 * classe TSqlSelect
 * Esta classe provê meios para manipulação de uma instrução de SELECT no banco de dados 
 */

final class TSqlSelect extends TSqlInstruction
{
	private $columns; //array de colunas a serem adicionadas
	
	/*
	 * método addColumn
	 * adiciona uma coluna a ser retornada pelo SELECT
	 * @param $column = coluna da tabela
	 */
	public function addColumn($column)
	{
		$this->columns[] = $column;
	}
	/*
	 * método getInstruction
	 * retorna a instrução de SELECT em forma de string
	 * 
	 */
	public function getInstruction()
	{
		//monta a instruição SELECT
		$this->sql = 'SELECT ';
		//monta a string com os nomes e colunas
		$this->sql .= implode(',', $this->columns);
		//adiciona na cláusula FROM o nome da tabela
		$this->sql .= ' FROM ' . $this->entity;
		
		//obtém a cláusula WHERE o objeto criteria
		if ($this->criteria)
		{
			$expression = $this->criteria->dump();
			if ($expression)
			{
				$this->sql .= ' WHERE ' . $expression; 
			}
			//obtém as propriedades do critério
			$order = $this->criteria->getProperty('order');
			$limit = $this->criteria->getProperty('limit');
			$offset = $this->criteria->getProperty('offset');

			//obtém a ordenação SELECT
			if($order)
			{
				$this->sql .= ' ORDER BY ' . $order;
			}
			if ($limit)
			{
				$this->sql .= ' LIMIT '. $limit;
			}
			if ($offset)
			{
				$this->sql .= ' OFFSET ' . $offset;
			}
		}
		return $this->sql;
	}
	
}