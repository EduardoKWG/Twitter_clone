<?php

namespace MF\Model;

use App\Connection;

//essa classe tem o objetivo de criar e retornar classes já com a conexão com o banco configurada

class Container {
	
	public static function getModel($model) {
		$class = "\\App\\Models\\".ucfirst($model);
		//faz a conexão com o DB 
		$conn = Connection::getDb();

		return new $class($conn);
	}
}

?>