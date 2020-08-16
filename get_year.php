<?php 
require_once('connection.php');

$getyear = $db -> select("year","*");

for($i = 0;$i < count($getyear);$i++){

  $result[$i]['year'] = $getyear[$i]['year'];
  $result[$i]['status'] = $getyear[$i]['status'];

}

echo json_encode($result);

?>