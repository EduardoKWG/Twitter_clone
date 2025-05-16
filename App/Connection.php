<?php
namespace App;

class Connection {
    public static function getDb() {
        try {
            $host = getenv('DB_HOST');
            $dbname = getenv('DB_DATABASE');
            $user = getenv('DB_USERNAME');
            $pass = getenv('DB_PASSWORD');

            $conn = new \PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);

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
