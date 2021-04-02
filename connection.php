<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header('Content-type: application/x-www-form-urlencoded');
header('Content-type: application/json');

date_default_timezone_set("Asia/Bangkok");



// If you installed via composer, just use this code to require autoloader on the top of your projects.
// require 'vendor/autoload.php';
require("Medoo.php");
 
// Using Medoo namespace
use Medoo\Medoo;
 
// $db = new Medoo([
// 	// required
// 	'database_type' => 'mysql',
// 	'database_name' => 'thaiawes_unescap',
// 	'server' => 'localhost',
// 	'username' => 'thaiawes_admin',
// 	'password' => 'chomart12',
// ]);

// $db = new Medoo([
// 	// required
// 	'database_type' => 'mysql',
// 	'database_name' => 'unescap',
// 	'server' => 'localhost',
// 	'username' => 'root',
// 	'password' => '12345678',
// ]);
$db = new Medoo([
	// required
	'database_type' => 'mysql',
	'database_name' => 'new_un',
	'server' => 'localhost',
	'username' => 'root',
	'password' => '',
]);

// phpinfo();
// $database->insert("account", [
// 	"user_name" => "foo",
// 	"email" => "foo@bar.com"
// ]);
?>