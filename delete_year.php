<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

		$getdata = $db ->delete('year',
			[ "AND" => [
					"year" => $_POST['year']
				]
			]
		);

		$getdata = $db ->select('year','*');
		
		for($i=0;$i<count($getdata);$i++){
				$result[$i]['year'] = $getdata[$i]['year'];
				$result[$i]['status'] = $getdata[$i]['status'];
		}

		echo json_encode($result);
?>