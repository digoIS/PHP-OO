<?php
// inclui as classes necessárias
include_once 'app.widgets/TElement.class.php';
include_once 'app.widgets/TImage.class.php';

//instancia o objeto imagem
$gnome = new TImage('app.images/gnome.png');
//exibe o objeto imagem
$gnome->show();

//instancia o objeto imagem
$gimp = new TImage('app.images/gimp.png');
//exibe o objeto imagem
$gimp->show();
