<?php 

require_once('connection.php');

$id = $_GET['id'];

$result = $db -> select("user_account","*",
[
    "id" => $id
]);

if(count($result) > 0){
    $db -> update("user_account",
    [
        "is_validation" => 1
    ],
    [
        "id" => $id
    ]);
}

?>