<?php 
require_once('connection.php');

$checkdb = $db -> count("year","*");

if($checkdb){
  
  $getyear = $db -> select("year","*",[
  "ORDER" => ["year" => 'ASC']
]);

    for($i = 0;$i < count($getyear);$i++){
      $result[$i]['year'] = $getyear[$i]['year'];
      $result[$i]['status'] = $getyear[$i]['status'];
    }

  echo json_encode($result);

}else{

  echo "[]";

}


?>