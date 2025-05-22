<?php
namespace App;
use Dotenv\Dotenv; 

class Connection {
    public static function getDb() {
        $dotenv = Dotenv::createImmutable(__DIR__.'/../../');
        $dotenv->load();

        try {
            $host = $_ENV['DB_HOST']; 
            $port = $_ENV['DB_PORT'];
            $dbname = $_ENV['DB_DATABASE'];
            $user = $_ENV['DB_USERNAME'];
            $pass = $_ENV['DB_PASSWORD'];

                echo "Tentando conectar em: host=$host, port=$port<br>";
                echo "Usuário: ".getenv('DB_USERNAME')."<br>";

            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
                \PDO::MYSQL_ATTR_SSL_CA => true, // SSL obrigatório no Railway
                \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4",

                \PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
                \PDO::MYSQL_ATTR_SSL_CA => false
            ];


            // acesso
            $conn = new \PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, $options);

            // Configura o modo de erro para exceções
            $conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return $conn;
        } catch (\PDOException $e) {

            error_log("Erro DB: " . $e->getMessage());
            die("Erro ao conectar ao banco. Tente novamente.");
        }
    }
}
?>
