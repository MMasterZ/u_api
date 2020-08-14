<?php 
require_once('connection.php');

$test = $db -> select("year","*");


for($i = 0;$i < count($test);$i++){

  $result[$i]['year'] = $test[$i]['year'];
  $result[$i]['status'] = $test[$i]['status'];

}

echo json_encode($result);

?>