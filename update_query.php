<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$uid = $_POST['uid'];
$query = $_POST['query'];
$checkExists = $db -> select("user_account","*",
[
    "id" => $uid
]);

if(count($checkExists) > 0){
    $db -> update("user_account",
    [
        "query" => $query
    ],
    [
        "id" => $uid
    ]);
    echo "1";
}else{
    echo "0";
}
?>