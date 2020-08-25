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
    
    $getdata = $db ->select('account','*');
        
    for($i=0;$i<count($getdata);$i++){
      $result[$i]['id'] = $getdata[$i]['id'];
      $result[$i]['username'] = $getdata[$i]['username'];
      $result[$i]['email'] = $getdata[$i]['email'];
      $result[$i]['password'] = $getdata[$i]['password'];
      $result[$i]['status'] = $getdata[$i]['status'];
    }

    echo json_encode($result);

?>