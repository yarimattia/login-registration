<?php
session_start();

//Initializing Globals Variables
$GLOBALS['config'] = array(
	'mysql'=> array(
		'host' => '127.0.0.1:3306',
		'username' => 'web_visitor',
		'password' => 'Real_Dog.9',
		'db' => 'login/registration'
	),
	'remember' => array(
		'cookie_name' => 'hash',
		'cookie_expiry' => 604800
	),
	'session' => array(	
		'session_name' => 'user',
		'token_name' => 'token'
	),
);

//Classes Loader
spl_autoload_register(function($class){
	require_once 'classes/' .$class .'.php';
	});
require_once 'functions/sanitize.php';
	
?>