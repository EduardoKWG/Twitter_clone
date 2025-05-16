<?php
namespace App;

class Connection {
    public static function getDb() {
        try {
            $host = getenv('DB_HOST');
            $port = getenv('DB_PORT');  // <-- pega a porta aqui
            $dbname = getenv('DB_DATABASE');
            $user = getenv('DB_USERNAME');
            $pass = getenv('DB_PASSWORD');

            // Inclui a porta na string de conexão
            $conn = new \PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $pass);

            // Configura o modo de erro para exceções
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (\PDOException $e) {
            echo "Erro ao conectar ao banco: " . $e->getMessage();
            exit;
        }
    }
}
?>
