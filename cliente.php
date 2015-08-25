<?php
//instancia cliente SOAP
$client = new SoapClient("exemplo.wsdl", array('encoding'=>'ISO-8859-1'));

try{
	//realiza chamada remotade método
	$retorno = $client->getNome(3);
	
	//imprime os dados de retorno
	echo '<table border=1 width=300>';
	echo '<tr bgcolor="#c0c0c0"><td>Coluna </td><td>Conteúdo </td></tr>';
	echo '<tr><td>Código </td><td>'. $retorno['id'].'</td></tr>';
	echo '<tr><td>Nome </td><td>' . $retorno['nome'] . '</td></tr>';
	echo '<tr><td>Endereco</td><td>'. $retorno['endereco'] . '</td></tr>';
	echo '<tr><td>Telefone</td><td>' . $retorno['telefone'] . '</td></tr>';
	echo '</table>';
}
catch (SoapFault $e){
	echo "Erro: ";
	echo "<b> {$e->faultstring} </b>";
}
?>