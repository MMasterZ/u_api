<?php 
require_once('connection.php');

$getyear = $db -> select("year","*",[
	"status" => 1,
	"ORDER" => ['year' => 'ASC']
]);

	for($i = 0;$i < count($getyear);$i++){
			$result[$i] = $getyear[$i]['year'];
	}

echo json_encode($result);

?>