<?php
//controlador das páginas restritas da aplicação configuradas de acordo com o usuário autenticado

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    
    //método para iniciar a sessão e validar se usuário está autenticado
    private function validarSessao(){
        session_start();
        if(empty($_SESSION['id']) || empty($_SESSION['nome'])){
            header('Location: /?login=erro');
            exit();
        }
    }
    public function infoUsuario(){
        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);
        $this->view->info_usuario = $usuario->getInfoUsuario();        
        $this->view->total_tweets = $usuario->getTotalTweets();        
        $this->view->total_seguindo = $usuario->getTotalSeguindo();        
        $this->view->total_seguidores = $usuario->getTotalSeguidores();
    }

    public function timeline(){
        $this->validarSessao();

        //recuperação dos tweets
        $tweet = Container::getModel('Tweet');
        
        $tweet->__set('id_usuario', $_SESSION['id']);        

        // variáveis de páginação
        $total_registro_pagina = 10;        
        $pagina = isset($_GET['pagina']) ? $_GET['pagina'] : 1;
        $deslocamento = ($pagina -1) * $total_registro_pagina;

        //$tweets = $tweet->getAll();
        $tweets = $tweet->getPorPagina($total_registro_pagina, $deslocamento);
        $total_tweets = $tweet->getTotalRegistros();

        $this->view->total_de_paginas = ceil($total_tweets['total'] / $total_registro_pagina);
        $this->view->pagina_ativa = $pagina;

        $this->view->tweets = $tweets; 

        //informação do usuário
        $this->infoUsuario();
        $this->render('timeline');
    }  
    

    public function tweet(){
        $this->validarSessao();

        $tweet = Container::getModel('Tweet');        
        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweet->salvar();

        header('Location: /timeline');    
    }

    public function removerTweet(){
        $this->validarSessao();

        if (isset($_POST['id_tweet'])) {
            $tweet = Container::getModel('Tweet');
            $tweet->__set('id', $_POST['id_tweet']);
            $tweet->__set('id_usuario', $_SESSION['id']);    
            $tweet->remover(); // Chama a função que fará a exclusão no banco de dados
        }
        header('Location: /timeline'); // Redireciona de volta para a timeline
    }

    public function quemSeguir(){
        $this->validarSessao();

        $pesquisarPor = isset($_GET['pesquisarPor']) ? $_GET['pesquisarPor'] : '';
       
        $usuarios = array();

        if($pesquisarPor != ''){
            $usuario = Container::getModel('Usuario');
            $usuario->__set('nome', $pesquisarPor);
            $usuario->__set('id', $_SESSION['id']);
            $usuarios = $usuario->getAll();
        }

        $this->view->usuarios = $usuarios;
        $this->infoUsuario();
        $this->render('quemSeguir');       
    }
    public function acao(){
        $this->validarSessao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id', $_SESSION['id']);

        if($acao =='seguir'){
            $usuario->seguirUsuario($id_usuario_seguindo);
        }else if($acao =='deixar_de_seguir'){
            $usuario->deixarSeguirUsuario($id_usuario_seguindo);
        }

        header('Location: /quem_seguir');
    }
}
?>