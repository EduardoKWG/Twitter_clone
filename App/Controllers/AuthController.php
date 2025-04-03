<?php
//controlador do processo de autenticação e saída da sessão 

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AuthController extends Action {

    public function autenticar(){
        $usuario = Container::getModel('Usuario');

        //recebe os dados do formulário de login
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $senha = trim($_POST['senha']);

        $usuario->__set('email', $email);
        $usuario->__set('senha', $senha);

        if ($usuario->autenticar()) {
            session_start();
            $_SESSION['id'] = $usuario->__get('id');
            $_SESSION['nome'] = $usuario->__get('nome');
            header('Location: /timeline');
            exit;
        } else {
            // Se as credenciais estiverem incorretas
            header('Location: /?login=erro');
            exit;
        }
    
    }

    public function sair(){
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }

}
?>