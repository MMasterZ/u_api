<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header('Content-type: application/x-www-form-urlencoded');
header('Content-type: application/json');

// If you installed via composer, just use this code to require autoloader on the top of your projects.
// require 'vendor/autoload.php';
require("Medoo.php");
 
// Using Medoo namespace
use Medoo\Medoo;
 
$db = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => 'untest',
	'server' => 'localhost',
	'username' => 'root',
	'password' => '123456789',
 
	// [optional]
	// 'charset' => 'utf8mb4',
	// 'collation' => 'utf8mb4_0900_ai_ci',
	// 'port' => 3306,
 
	// [optional] Table prefix
	// 'prefix' => 'PREFIX_',
 
	// [optional] Enable logging (Logging is disabled by default for better performance)
	// 'logging' => true,
 
	// [optional] MySQL socket (shouldn't be used with server and port)
	// 'socket' => '/tmp/mysql.sock',
 
	// [optional] driver_option for connection, read more from http://www.php.net/manual/en/pdo.setattribute.php
	// 'option' => [
	// 	PDO::ATTR_CASE => PDO::CASE_NATURAL
	// ],
 
	// [optional] Medoo will execute those commands after connected to the database for initialization
	// 'command' => [
	// 	'SET SQL_MODE=ANSI_QUOTES'
	// ]
]);
 
// $database->insert("account", [
// 	"user_name" => "foo",
// 	"email" => "foo@bar.com"
// ]);
?>