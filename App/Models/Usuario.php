<?php
namespace App\Models;
use MF\Model\Model;

class Usuario extends Model{
    // Colunas de registro no DB
    private $id;
    private $nome;
    private $email;
    private $senha;

    public function __get($atributo){
        return $this->$atributo;
    }

    public function __set($atributo, $valor){
        $this->$atributo = $valor;
    }
    //verifica se o email ja foi cadastrado
    public function emailRepetido() {
        $query = "SELECT id FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();
    
        return $stmt->rowCount() > 0; // Retorna true se já existir, false se não existir
    }

    // Salvar
    public function salvar(){
        if ($this->emailRepetido()) {
            return "Email já cadastrado!";
        }
        $query = "INSERT INTO usuarios(nome, email, senha) VALUES(:nome, :email, :senha)";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', $this->__get('nome'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':senha', $this->__get('senha'));
        $stmt->execute();

        return $this;
    }

    // Validar se um cadastro pode ser feito
    public function validarCadastro(){
        $valido = true;
    
        // Armazena os campos para validação
        $campos = [$this->__get('nome'), $this->__get('email'), $this->__get('senha')];
    
        // Filtra os campos e verifica se algum tem menos de 3 caracteres
        $valido = count(array_filter($campos, fn($campo) => strlen($campo) <= 3)) === 0;
    
        // Se não houver campos com menos de 3 caracteres, $valido será true, senão será false
        return $valido;
    }

    // Recuperar um usuario por email
    public function getUsuarioPorEmail(){
        $query = "SELECT nome, email FROM usuarios WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    // Autenticar usuário
    public function autenticar(){
        // Corrigir a consulta SQL para garantir que está esperando dois parâmetros
        $query = "SELECT id, nome, email, senha FROM usuarios WHERE email=:email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();

        $usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($usuario && !empty($usuario['id']) && !empty($usuario['nome'])) {
            // Verifica se a senha fornecida corresponde ao hash da senha no banco
            if (password_verify($this->__get('senha'), $usuario['senha'])) {
                $this->__set('id', $usuario['id']);
                $this->__set('nome', $usuario['nome']);
                return true;
            }
        }
        return false;
    }

    public function getAll(){
        //estamos fazendo uma consulta que retorna as colunas do usuario e uma coluna adicional gerada pela subquery que indica se o usuario pesquisador está seguindo  esse outro (1 para sim e 0 para não) 
        $query = "
        select 
            u.id, 
            u.nome, 
            u.email,
            (
                select 
                    count(*)
                from 
                    usuarios_seguidores as us
                where
                    us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
            ) as seguindo_sn
        from 
            usuarios as u
        where 
            u.nome like :nome and u.id != :id_usuario";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function seguirUsuario($id_usuario_seguindo){
    $query = 'insert into usuarios_seguidores(id_usuario, id_usuario_seguindo) values (:id_usuario, :id_usuario_seguindo)';
    $stmt = $this->db->prepare($query);
    $stmt->bindValue(':id_usuario', $this->__get('id'));
    $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
    $stmt->execute();
    return true;
    }

    public function deixarSeguirUsuario($id_usuario_seguindo){
        $query = 'delete from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
        $stmt->execute();
        return true;
    }
    //Informações do usuario
    public function getInfoUsuario(){
        $query = 'select nome from usuarios where id = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    //total de tweets
    public function getTotalTweets(){
        $query = 'select count(*) as total_tweet from tweets where id_usuario = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    //Total de usuários que estamos seguindo
    public function getTotalSeguindo(){
        $query = 'select count(*) as total_seguindo from usuarios_seguidores where id_usuario = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    //Total de seguidores
    public function getTotalSeguidores(){
        $query = 'select count(*) as total_seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id'));
        $stmt->execute();
        
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
?>
