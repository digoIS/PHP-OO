<?php
/*
 * classe TSqlInstruction
 * Esta classe provê os métodos em comum entre todas instruções
 * SQL (SELECT , INSERT, DELETE E UPDATE)
 */
abstract class TSqlInstruction
{
	protected $sql;
	protected $criteria;
	protected $entity;
	
	/*
	 * método setEntity()
	 * define o nome da entidade (tabela) manipulada pela instrução SQL
	 * @param $entity = tabela
	 */
	
	final public function setEntity($entity)
	{
		$this->entity = $entity;
	}
	/*
	 * metodo getEntity()
	 * retorna o nome da entidade (tabela)
	 */
	final public function getEntity()
	{
		return $this->entity;
	}
	
	/*
	 * método setCriteria()
	 * Define um critério de seleção dos dados através da composição de um objeto
	 * do tipo TCriterio,que oferece uma interface para definição de critérios
	 * @param $criteria = obejto do tipo TCriteria 
	 */
	public function setCriteria(TCriteria $criteria)
	{
		$this->criteria = $criteria;
	}
	
	/*
	 * metodo getInstruction()
	 * declarando-o como <abstract> obrigamos sua declaração nas classes filhas
	 * uma vez que seu comprtamento seá distinto eem cada uma delas, configurando o polimorfismo
	 */
	abstract function getInstruction();
}