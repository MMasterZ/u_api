<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

		$db->delete('country_data',
			[ "AND" => [
					"year" => $_POST['year'],
					"exp_country" => $_POST['code']
				]
			]
		);

		$db->delete('upload_log',
		[ "AND" => [
				"data_year" => $_POST['year'],
				"country" => $_POST['country']
			]
		]);

		$checkdb = $db -> count("upload_log","*");

			if($checkdb){
				
				$getdata = $db -> select("upload_log","*");

					for($i = 0;$i < count($getdata);$i++){
						$result[$i]['id'] = $getdata[$i]['id'];
						$result[$i]['country'] = $getdata[$i]['country'];
						$result[$i]['data_year'] = $getdata[$i]['data_year'];
						$result[$i]['lastUpdate'] = $getdata[$i]['lastUpdate'];
					}

				echo json_encode($result);

			}else{

				echo "[]";

			}

?>