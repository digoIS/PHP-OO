<?php
function __autoload($classe)
{
	if (file_exists("app.widgets/{$classe}.class.php"))
	{
		include_once "app.widgets/{$classe}.class.php";
	}
}

//exibee uma mensagem de informção
new TMessage('info', 'Esta ação é inofensiva, isto é só um lembrete');