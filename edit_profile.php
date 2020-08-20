<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

    $db -> update('account',[
        "username" => $_POST['username'],
        "email" => $_POST['email'],
        "password" => $_POST['password']
    ],
    [
        "id" => $_POST['id']
    ]);

    echo "Success";

?>