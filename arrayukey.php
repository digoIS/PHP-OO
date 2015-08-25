<?php
$time = microtime(true);
$reposta = Array();

//base de dados de locais

$base = &$resposta[];

$base = Array();
$base['id'] = 2;
$base['pai'] = 1;
$base['local'] = 'Guaruja'; 

$base = &$resposta[];

$base = Array();
$base['id'] = 3;
$base['pai'] = null;
$base['local'] = 'Santa Catarina';

$base = &$resposta[];

$base = Array();
$base['id'] = 4;
$base['pai'] = 3;
$base['local'] = 'Blumenau';

$base = &$resposta[];

$base = Array();
$base['id'] = 6;
$base['pai'] = 3;
$base['local'] = 'Joinville' ;

$base = &$resposta[];

$base = Array();
$base['id'] = 1;
$base['pai'] = null;
$base['local'] = 'Sao Paulo';

//separando os pais e filhos
foreach($resposta as &$noh)
{
	if($noh['pai'] == null) $pais[] = $noh;
	if($noh['pai'] != null) $filhos[] = $noh;
}


asort($pais);
asort($filhos);

//pra cada pai aglutina os filhos
foreach($pais as $pai)
{
	$arvore[] = $pai;
	foreach($filhos as $filho)
	{
		if($filho['pai'] == $pai['id']) $arvore[] = $filho;
	}
	
}


echo"pais:<br/>";
print_r($pais);
echo"<br/>";
echo"<br/>";



echo"filhos:<br/>";
print_r($filhos);

echo"<br/>";
echo"<br/>";
echo"<br/>";
echo"<br/>";
echo "Veja a arvore <br />";
print_r($arvore);

var_dump(microtime(true) - $time);