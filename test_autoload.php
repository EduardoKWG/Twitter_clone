<?php
// Inclui o autoloader gerado pelo Composer
require 'vendor/autoload.php';

// Agora, vamos usar uma classe da dependência que você acabou de instalar.
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

// Criando um logger
$log = new Logger('name');
$log->pushHandler(new StreamHandler('app.log', Logger::WARNING));

// Testando se o log funciona
$log->warning('Foo');
$log->error('Bar');

echo "Logger funcionando! Veja o arquivo app.log para os logs.\n";
?>