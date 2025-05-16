<?php
namespace App;

class Connection {
    public static function getDb() {
        try {
            $conn = new \PDO(
                "mysql:host=" . getenv('MYSQLHOST') . ";port=" . getenv('MYSQLPORT') . ";dbname=" . getenv('MYSQLDATABASE') . ";charset=utf8",
                getenv('MYSQLUSER'),
                getenv('MYSQLPASSWORD')
            );
            return $conn;
        } catch (\PDOException $e) {
            echo 'Erro ao conectar ao banco: ' . $e->getMessage();
            exit;
        }
    }
}
?>
