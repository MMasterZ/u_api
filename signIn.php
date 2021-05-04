<?php
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$email = $_POST['email'];
$password = $_POST['password'];

$checkExists = $db -> select("user_account","*",
[
    "email" => $email,
    "password" => $password,
    "is_validation" => "1"
]);

if(count($checkExists) > 0){
    echo json_encode($checkExists);
}else{
    echo "0";
}


 ?>