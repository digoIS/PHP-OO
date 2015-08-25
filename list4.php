<?php
//meeptodo autoload()
function __autoload($classe)
{
	$pastas = array("app.ado","app.widgets");
	foreach ($pastas as $pasta )
	{
		if(file_exists("{$pasta}/{$classe}.class.php"))
		{
			include_once "{$pasta}/{$classe}.class.php";
		}
	}
	
}
//declara a classe Pessoa
class Pessoa extends TRecord
{
	const TABLENAME = 'aluno';
}
/**
 * classe PessoaList
 */
class PessoasList extends TPage
{
	private $loaded;
	private $datagrid;
	public function __construct()
	{
		parent::__construct();
		
		//instancia objeto TDataGrid
		$this->datagrid = new TDataGrid;
		
		//instancia as colunas a Datagrid
		$codigo 	= new TDataGridColumn('id',			'Codigo', 			'left', 50);
		$nome		= new TDataGridColumn('nome',		'Nome',				'left',	180);
		$endereco 	= new TDataGridColumn('endereco', 	'Endereco', 		'left', 140);
		$qualifica	= new TDataGridColumn('qualifica',	'Qualificações',	'left', 200);

		//adiciona as colunas à DataGrid
		$this->datagrid->addColumn($codigo);
		$this->datagrid->addColumn($nome);
		$this->datagrid->addColumn($endereco);
		$this->datagrid->addColumn($qualifica);
		
		//instancia duas ações da DataGrid
		$action1 = new TDataGridAction(array($this, 'onDelete'));
		$action1->setLabel('Deletar');
		$action1->setImage('delete.png');
		$action1->setField('id');
		
		$action2 = new TDataGridAction(array($this, 'onView'));
		$action2->setLabel('Visualizar');
		$action2->setImage("ico_view.png");
		$action2->setField('id');
		
		//adiciona as ações à DataGrid
		$this->datagrid->addAction($action1);
		$this->datagrid->addAction($action2);
		
		//cria o modelo da DataGrid, montando sua estrutura
		$this->datagrid->createModel();
		
		//adiciona a DataGrid à página
		parent::add($this->datagrid);
	}
	/**
	 * função onReload()
	 * carrega a DataGrid com os objetos do banco de dados
	 * 
	 */
	function onReload()
	{
		TTransaction::open('pg_livro');

		//instancia um repositório para Pessoa
		$repository = new TRepository('pessoa');
		//cria um critério, definindo a ordenação
		$criteria = new TCriteria;
		$criteria->setProperty('order', 'id');
		//carrega os objetos $pessoas
		$pessoas = $repository->load($criteria);
		$this->datagrid->clear();
		if($pessoas)
		{
			foreach($pessoas as $pessoa)
			{
				//adiciona o objeto na DataGrid
				$this->datagrid->addItem($pessoa);
			}
		}
		//finaliza a transação
		TTransaction::close();
		$this->loaded = true;
	}
	/**
	 * função onDelete()
	 * executada quando o usuário clicar no botão excluir
	 */
	function onDelete($param)
	{
		//obtém o parâmetro e eexibe a mensagem
		$key=$param['key'];
		
		$action1 = new TAction(array($this, 'Delete'));
		$action2 = new TAction(array($this, 'teste'));
		
		$action1->setParameter('key', $key);
		$action2->setParameter('key', $key);
		
		new TQuestion('Deseja realmente excluir o registro?', $action1, $action2);
	}
	/**
	 * função Delete()
	 * exclui um registro
	 */
	function Delete($param)
	{
		// obtém o parâmetro ee exibe a mensagem
		$key=$param['key'];
		//inicia  transação ccom o banco 'pg_livro'
		TTransaction::open('pg_livro');
		
		$pessoa = new Pessoa($key);
		$pessoa->delete();
		//finaliza a transação
		TTransaction::close();
		new TMessage('info', "Registro excluído ccom sucesso");
		$this->onReload();
	}
	/**
	 * função onView()
	 * executada qquando o usuário clicar no botão visualizar
	 */
	function onView($param)
	{
		//obtém o parâmetro e exibe a mensagem
		$key=$param['key'];
		//inicia transação com o banco 'pg_livro'
		TTransaction::open('pg_livro');
		
		$pessoa = new Pessoa($key);
		//finaliza a transação
		TTransaction::close();
		$mensagem = "O nome é: $pessoa->nome<br>";
		$mensagem.= " O Endereco é $pessoa->endereco";
		new TMessage('info', $mensagem);
		$this->onReload();
	}
	/**
	 * função show()
	 * executada quando o usuário clicar no botão excluir
	 */
	function show()
	{
		if(!$this->loaded)
		{
			$this->onReload();
		}
		parent::show();
	}
}

$page = new PessoasList();
$page->show();
	