<?php
/**
 * função autload()
 * carrega uma classe quand ela é necessária
 * ou seja, quando ela é instancia pela primeira vez
 */
function __autoload($classe)
{
	if (file_exists("app.widgets/{$classe}.class.php"))
	{
		include_once "app.widgets/{$classe}.class.php";
	}
}
//cria o formulário 
$form = new TForm('form_pessoas');

//cria um painel
$panel = new TPanel(400, 200);

//adciona o painel ao formulário
$form->add($panel);

//cria um rótulo para título]
$titulo = new TLabel('Exemplo de formulário');
$titulo->setFontFace('Arial');
$titulo->setFontColor('red');
$titulo->setFontSize(18);

//posiciona o titulo no painle
$panel->put($titulo, 120, 4);

$imagem = new TImage('app.images/mouse.png');
//posiciona a imagem no painel
$panel->put($imagem, 320, 120);

//cria os campos do formulário
$codigo		= new TEntry('codigo');
$nome		= new TEntry('nome');
$endereco	= new TEntry('endereco');
$telefone	= new TEntry('telefone');
$cidade		= new TCombo('cidade');

$items = array();
$items['1'] = 'Porto Alegre';
$items['2'] = 'Lajeado';

//adiciona as opçoes na combo
$cidade->addItems($items);

//ajusta o tamanho destes campis
$codigo->setSize(70);
$nome->setSize(140);
$endereco->setSize(140);
$telefone->setSize(140);
$cidade->setSize(140);

//cria os rotulos do texto
$label1=new TLabel('Cod	igo');
$label2=new TLabel('Nome');
$label3=new TLabel('Cidade');
$label4=new TLabel('Endereco');
$label5=new TLabel('Telefone');

//posiciona os campos e os rótulos dentrod do painel
$panel->put($label1, 10, 40);
$panel->put($codigo, 10, 60);
$panel->put($label2, 10, 90);
$panel->put($nome, 10, 110);
$panel->put($label3, 10, 140);
$panel->put($cidade, 10, 160);
$panel->put($label4, 200, 40);
$panel->put($endereco, 200, 60);
$panel->put($label5, 200, 90);
$panel->put($telefone, 200, 110);

//exibe o formulário
$form->show();