<?php
// inclui as classes necessárias
include_once 'app.widgets/TElement.class.php';
include_once 'app.widgets/TParagraph.class.php';

//instancia um objeto parágrafo
$texto1 = new TParagraph('teste1<br>teste1<br>teste1');
$texto1->setAlign('left');

//exibe o objeto
$texto1->show();

//instancia o objeto páragrafo
$texto2 = new TParagraph('<br>teste2<br>teste2<br>teste2');
$texto2->setAlign('right');

//exibe o objeto
$texto2->show();