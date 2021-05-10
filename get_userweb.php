<?php 
require_once('connection.php');

  $checkdb = $db -> count("user_account","*");

  if($checkdb){
    
    $getdata = $db -> select('user_account','*',["ORDER"=>"id"]);
      
      for($i = 0;$i < count($getdata);$i++){

        $result[$i]['id'] = $getdata[$i]['id'];
        $result[$i]['email'] = $getdata[$i]['email'];
        $result[$i]['password'] = $getdata[$i]['password'];
        $result[$i]['country'] = $getdata[$i]['country'];
        $result[$i]['dateTime'] = $getdata[$i]['dateTime'];
        $result[$i]['organization'] = $getdata[$i]['organization'];
        $result[$i]['is_validation'] =$getdata[$i]['is_validation'];
        $result[$i]['is_subscribe'] = $getdata[$i]['is_subscribe'];
        $result[$i]['query'] = $getdata[$i]['query'];
      }

      echo json_encode($result);

  }else{

    echo "[]";

  }

  

?>