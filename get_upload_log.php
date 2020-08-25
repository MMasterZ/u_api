<?php 
require_once('connection.php');

$checkdb = $db -> count("upload_log","*");

if($checkdb){
  
  $getdata = $db -> select("upload_log","*");

    for($i = 0;$i < count($getdata);$i++){
      $result[$i]['id'] = $getdata[$i]['id'];
      $result[$i]['country'] = $getdata[$i]['country'];
      $result[$i]['year'] = $getdata[$i]['year'];
      $result[$i]['lastUpdate'] = $getdata[$i]['lastUpdate'];
    }

  echo json_encode($result);

}else{

  echo "[]";

}


?>