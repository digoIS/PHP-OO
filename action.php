<?php
include_once 'app.widgets/TAction.class.php';

class Receptor
{
	function acao($parameter)
	{
		echo "Ação executaa com sucesso\n<br>";
	}
}

$receptor = new Receptor();
$action1 = new TAction(array($receptor, 'acao'));
$action1->setParameter('nome', 'marcio');
echo $action1->serialize();
echo "<br>\n";

$action2 = new TAction('strtoup');
$action2->setParameter('nome', 'marcio');
echo $action2->serialize();