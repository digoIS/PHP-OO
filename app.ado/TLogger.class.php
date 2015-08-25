<?php
/*
 * classe Tlogger
 * Esta classe provê uma inteface abstrata para definição de algoritimos de LOG
 */
abstract class TLogger
{
	protected $filename; //local do arquivo de log
	/*
	 * método __construct()
	 * instancia um logger
	 * @param $filename = local de arquivo de LOG
	 *  
	 */
	
	public function __construct($filename)
	{
		$this->filename = $filename;
		//reseta o conteúdo do arquivo
		file_put_contents($filename, '');
	}
	
	//define o método write como obrigatório
	abstract function write($message);
}