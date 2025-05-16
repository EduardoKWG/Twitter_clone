<?php

namespace App;

class Connection {

    public static function getDb() {
        try {
            $host = getenv('DB_HOST') ?: 'localhost';
            $dbname = getenv('DB_NAME') ?: 'twitter_clone';
            $user = getenv('DB_USER') ?: 'root';
            $pass = getenv('DB_PASS') ?: '';

            $conn = new \PDO(
                "mysql:host=$host;dbname=$dbname;charset=utf8",
                $user,
                $pass
            );
                
            return $conn;

        } catch (\PDOException $e) {
            // Se houver um erro, exibe a mensagem
            echo 'Erro ao conectar ao banco: ' . $e->getMessage();
            exit;
        }
    }
}

?>
