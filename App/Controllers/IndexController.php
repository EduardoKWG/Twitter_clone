<?php
// controlador das páginas "externas", antes do processo de login

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class IndexController extends Action {

	public function index() {
		$this->view->login = isset($_GET['login']) ? $_GET['login'] :'';
		$this->render('index');

	}

	public function inscreverse(){
		$this->view->usuario = array(
			'nome' => '',
			'email' => '',
			'senha' =>''
		);
		$this->view->erroCadastro = false;
		$this->view->emailRepetido = false;
		$this->render('inscreverse');
	}

	public function registrar(){

		$usuario = Container::getModel('Usuario');

		// Sanitização básica dos inputs por questão de segurança
		$nome = filter_var(trim($_POST['nome']), FILTER_SANITIZE_STRING);
		$email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
		$senha = trim($_POST['senha']);

		//verifica se os campos obrigatórios foram preenchidos
		if(empty($nome) || empty($email) || empty($senha)){
			$this->view->erroCadastro = true;
			$this->view->usuario =[
				'nome' => $nome,
				'email' => $email
			];
			$this->render('inscreverse');
			return;
		}

		$usuario->__set('nome', $nome);
		$usuario->__set('email', $email);
		
		//gerar hash seguro da senha
		$hash = password_hash($senha, PASSWORD_DEFAULT);
		$usuario->__set('senha', $hash);
		
		
		$usuariosEncontrados = $usuario->getUsuarioPorEmail();

		$this->view->erroCadastro = false;
		$this->view->emailRepetido = false;
		
		//se os dados forem válidos e não houver email já registrado:
		if($usuario->validarCadastro() && count($usuariosEncontrados) == 0){
			$usuario->salvar();
			$this->render('cadastro');
        exit;

		} //se os dados forem válidos mas já existe um email cadastrado:
		elseif($usuario->validarCadastro() && count($usuariosEncontrados) !== 0){
			$this->view->usuario = array(
				'nome' => $nome,
				'email' => $email,
			);

			$this->view->emailRepetido = true;
			$this->render('inscreverse');

		} //se os dados forem inválidos
		else {
			$this->view->usuario = array(
				'nome' => $nome,
				'email' => $email,
			);

			$this->view->erroCadastro = true;
			$this->render('inscreverse');
		}
	}
}
?>