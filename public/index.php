<?php

	//ini_set('error_reporting', 'E_STRICT');

	require_once __DIR__ . '/../vendor/autoload.php';

	$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
	$dotenv->load();

	$route = new \App\Route;
?>