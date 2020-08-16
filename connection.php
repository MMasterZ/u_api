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
]);
 
// $database->insert("account", [
// 	"user_name" => "foo",
// 	"email" => "foo@bar.com"
// ]);
?>