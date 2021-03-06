<?php
/*
 * classe TRecord
 * Esta classe provê os métodos necessários para persistir e
 * recuperar objetos da base de dados (Active Record)
 */
abstract class TRecord
{
	protected $data; //array contendo os dados do objeto
	
	/*método construct()
	 * ibstancia um Active Record. Se passado $id, já carrega o objeto
	 * @param [$id] = ID do objeto
	 */
	public function __construct($id = NULL)
	{
		if($id) //se o ID for informado
		{
			// carrega o objeto correspondente
			$object = $this->load($id);
			{
				if ($object)
				{
					$this->fromArray($object->toArray());
				}
			}
		}
	}
	/*
	 * método __clone()
	 * executado qunaod o objeto for clonado
	 * limpa o ID para que seja gerado um novo ID para o clone
	 */
	public function __clone()
	{
		unset($this->id);
	}
	/*
	 * método __set()
	 * executado sempre que uma propriedade for atribuida
	 */
	public function __set($prop, $value)
	{
		//verifica se existe método set_<propriedade>
		if(method_exists($this, 'set_'.$prop))
		{
			//executa o método set_<propriedade>
			call_user_func(array($this, 'set_'.$prop), $value);		
			
		}
		else
		{
			if($value === NULL)
			{
				unset($this->data[$prop]);
			}
			else
			{
				// atribui o valor da propriedade
				$this->data[$prop] = $value;
			}
		}
	}
	/*
	 * método function __get()
	 * executado sempre que uma propriedade for requerida
	 */
	public function __get($prop)
	{
		//verifica se o método get_<propriedade>
		if (method_exists($this, 'get_'.$prop))
		{
			//executa o método get_<propriedade>
			return call_user_func(array($this, 'get_'.$prop));
		}
		else
		{
			//retorna o valor da propriedade
			if(isset($this->data[$prop]))
			{
				return $this->data[$prop];
			}
		}
	}
	
	/*
	 * método getEntity()
	 * retorna o nome da entidade (tabela)
	 */
	private function getEntity()
	{
		//obtém o nome da classe
		$class = get_class($this);
		//retorna a constante de calsse TABLENAME
		return constant("{$class}::TABLENAME");
	}
	/*
	 * método fromArray()
	 * preenche os dados do objeto como array
	 */
	public function fromArray($data)
	{
		$this->data = $data;
	}
	/*
	 * método toArray
	 * retorna os dados do objeto como array
	 */
	public function toArray()
	{
		return $this->data;
	}
	/*
	 * método store()
	 * armazena o bojeto na base de dados e retorna
	 * o número de linhas afetadas pela instruçãoSQL (zero ou um)
	 */
	public function store()
	{
		//verifica se tem um ID ou se existe na base de dados
		if(empty($this->data['id']) or (!$this->load($this->id)))
		{
			//incrementa o id
			if(empty($this->data['id']))
			{
				$this->id = $this->getLast() + 1;
			}
			//cria uma instrução de insert
			$sql = new TSqlInsert();
			$sql->setEntity($this->getEntity());
			//percorre os dados do objeto
			foreach($this->data as $key => $value)
			{
				// passa os dados do objeto para o SQL
				$sql->setRowData($key, $this->$key);
			}
		}
		else
		{
			//instancia instrução de update
			$sql = new TSqlUpdate();
			$sql->setEntity($this->getEntity());
			//cria um critério de seleção baseado no ID
			$criteria = new TCriteria();
			$criteria->add(new TFilter('id', '=', $this->id));
			$sql->setCriteria($criteria);
			//percorre os dados do objeto
			foreach($this->data as $key => $value)
			{
				if($key !== 'id') // o id não precir ir no UPDATE
				{
					//passa os dados do objeto para o SQL
					$sql->setRowData($key, $this->$key);
				}
			}
		}
		//obtém transação ativa
		if ($conn = TTransaction::get())
		{
			//faz o log e executa o SQL
			TTransaction::log($sql->getInstruction());
			$result = $conn->exec($sql->getInstruction());
			//retorna o resultado $result
			return $result;
		}
		else
		{	//se não tiver transação, retorna uma exceção
			throw new Exception('Não há transação ativa!');
		}
	}
	/*
	 * método load()
	 * recupera (retorna) um bojeto da base de dados
	 * através de seu ID e instancia ele na memória
	 * @param $id = ID do objeto
	 */
	public function load($id)
	{
		//instancia a instrução de SELECT
		$sql = new TSqlSelect();
		$sql->setEntity($this->getEntity());
		$sql->addColumn('*');
		
		//cria um critŕio de seleção de dados
		$criteria = new TCriteria();
		$criteria->add(new TFilter('id', '=', $id));
		//define o critério de seleção de dados
		$sql->setCriteria($criteria);
		//obtém a transação ativa
		if ($conn  = TTransaction::get())
		{
			//cria mensagem de log e executa a consulta
			TTransaction::log($sql->getInstruction());
			$result = $conn->Query($sql->getInstruction());
			//se retornou algum dado
			if($result)
			{
				//retorna os dados em forma de objeto
				$object = $result->fetchObject(get_class($this));
			}
			return $object; 
		}
		else
		{
			//se não tiver transação, retorna uma execução
			throw new Exception('Não há transação ativa!!');
		} 

	}
	/*
	 * método delete
	 * exclui um objeto da base de dados atraves de seu ID
	 * @param $id = ID do objeto
	 */
	public function delete($id = NULL)
	{
		// o ID o parâmetro oua propriedade ID
		$id = $id ? $id : $this->id;
		//instancia  uma instrução de DELETE
		$sql = new TSqlDelete();
		$sql->setEntity($this->getEntity());
		
		//cria um critério de seleção de dados
		$criteria = new TCriteria();
		$criteria->add(new TFilter('id', '=', $id));
		//define o critério de seleção baseado no ID
		$sql->setCriteria($criteria);
		
		//obtém transação ativa
		if($conn = TTransaction::get())
		{
			//faz o log e executa o SQL
			TTransaction::log($sql->getInstruction());
			$result = $conn->exec($sql->getInstruction());
			//retorna o resultado
			return $result; 
		}
		else
		{
			//se não tiver transação, retorna uma exceção
			throw new Exception('Não há transação ativa!!');	
		}
		
		
	}
	/*
	 * método getLast()
	 * retorna o o último ID
	 * 
	 */
	private function getLast()
	{
		//inicia a transação
		if($conn = TTransaction::get())
		{
			$sql = new TSqlSelect();
			$sql->addColumn('max(ID) as ID');
			$sql->setEntity($this->getEntity());
			//cria o log e executa intrução SQL
			TTransaction::log($sql->getInstruction());
			$result = $conn->query($sql->getInstruction());
			//retorna os dados do banco
			$row = $result->fetch();
			return $row[0];
		}
		else 
		{
			//se não tiver transação, retorna uma exceção
			throw new Exception('Não há transação ativa!!'); 	
		}
	}	
}
