<?php

namespace App;

class Connection {

	public static function getDb() {
		try {

			$conn = new \PDO(
				"mysql:host=localhost;dbname=twitter_clone;charset=utf8",
				"root",
				"" 
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