<?php 
require_once('connection.php');

$_POST = json_decode(file_get_contents("php://input"),true);

if(empty($_POST['name']) || empty($_POST['password'])){
  $error = true;
}

if($error){
  echo "Error";
}else{
  $db->insert('test',[
    'name' => $_POST['name'],
    'details' => $_POST['password'],
    'status' => 1
  ]);
   echo "Success";
}

?>