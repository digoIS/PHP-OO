<?php
/*
 * classe TFilter
 *  Esta classe provê uma interface para definição de filtros de seleção 
 */

class TFilter extends TExpression
{
	private $variable; //variavel
	private $operator; //operador
	private $value; //valor
	
	/*
	 * metodo __consttruct()
	 * instancia um novo filtro
	 * @param $variable = vairavel
	 * @param $operator = oerador (>,<)
	 * @param $value = valor a ser comparado
	 */
	
	public function __construct($variable, $operator, $value)
	{
		//armazena as propriedades
		$this->variable = $variable;
		$this->operator = $operator;
		$this->value = $value;
		
		//transforma o valor de acordo com certas regras
		//antes de atribuir a propriedade $this->value
		
		$this->value = $this->transform($value);
	}
	/*
	 * metodo transform()
	 * recebe um valor e faz as modificações necessarias
	 * para ele ser interpretado pelo banco de dados
	 * podendo ser um integer/string/boolean ou array
	 * @param $value = valor a ser transformado
	 *   
	 */
	
	private function transform($value)
	{
		//caso ele seja um array
		if(is_array($value))
		{
			//percorre os valores
			foreach ($value as $x)
			{
				//se for um inteiro
				if (is_integer($x))
				{
					$foo[] = $x;
				}
				else if (is_string($x))
				{
					//se for string, adiciona aspas
					$foo[] = "'$x'";
				}
			}
			//converte o array em string separada por ","
			$result = '(' . implode(',',$foo) . ')';
		}
		
		//caso seja uma string
		else if (is_string($value))
		{
			//adiciona aspas
			$result = "'$value'";
		}
		
		//caso seja valor nulo
		else if (is_null($value))
		{
			//armazena NULL
			$result = 'NULL';
		}
		
		//caso seja booleano
		else if (is_bool($value))
		{
			//armazena TRUE ou FALSE
			$result = $value ? 'TRUE' : 'FALSE';
		}
		else 
		{
			$result = $value;
		}
		//retorna o valor 
		return $result;
	}
	
	/*
	 * metodo dump()
	 * retorna o filtro em forma de expressão
	 * 
	 */
	public function dump()
	{
		//concatena a expressao
		return "{$this->variable} {$this->operator} {$this->value}";
	}
}
