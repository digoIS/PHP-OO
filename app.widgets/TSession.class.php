<?php
/**
 * classe TSession
 * gerencia uma sessão com o usuário
 */
class TSession
{
	/**
	 * método construtor
	 * inicializa uma sessão
	 */
	
	public function __construct()
	{
		session_start();
	}
	/**
	 * método setValue()
	 * aarmazena uma variável na sessão
	 * @param $var = Nome da variável
	 * @param $value = valor
	 */
	public static function setValue($var, $value)
	{
		$_SESSION[$var] = $value;
	}
	/**
	 * método getValue()
	 * retorna uma variável de sessão
	 * @param $var = nome da variável
	 */
	public static function getValue($var)
	{
		if (isset($_SESSION[$var]))
		{
			return $_SESSION[$var];
		}
		
	}
	/**
	 * método freeSession()
	 * destrói os dados de uma sessão
	 */
	public static function freeSession()
	{
		$_SESSION = array();
		session_destroy();
	}
	
}