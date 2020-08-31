<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

 $table = $_POST['code'] . "_" . $_POST['year'];

		// $db->delete('country_data',
		// 	[ "AND" => [
		// 			"year" => $_POST['year'],
		// 			"exp_country" => $_POST['code']
		// 		]
		// 	]
		// );

		$db -> drop($table);

		$db->delete('upload_log',
		[ "AND" => [
				"year" => $_POST['year'],
				"country" => $_POST['country']
			]
		]);

		$checkdb = $db -> count("upload_log","*");

			if($checkdb){
				
				$getdata = $db -> select("upload_log","*");

					for($i = 0;$i < count($getdata);$i++){
						$result[$i]['id'] = $getdata[$i]['id'];
						$result[$i]['country'] = $getdata[$i]['country'];
						$result[$i]['year'] = $getdata[$i]['year'];
						$result[$i]['last_update'] = $getdata[$i]['last_update'];
					}

				echo json_encode($result);

			}else{

				echo "[]";

			}

?>