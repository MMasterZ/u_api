<?php 
require_once('connection.php');

    $getdata = $db -> select('account','*');
    
    for($i = 0;$i < count($getdata);$i++){

      $result[$i]['id'] = $getdata[$i]['id'];
      $result[$i]['username'] = $getdata[$i]['username'];
      $result[$i]['email'] = $getdata[$i]['email'];
      $result[$i]['password'] = $getdata[$i]['password'];

    }

echo json_encode($result);

?>