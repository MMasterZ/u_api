<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

		$getdata = $db ->delete('user',
			[ "AND" => [
					"id" => $_POST
				]
			]
		);

		$checkdb = $db -> count("user","*");

		if ($checkdb) {

			$getdata = $db ->select('user','*');
			
				for($i=0;$i<count($getdata);$i++){
					$result[$i]['id'] = $getdata[$i]['id'];
        			$result[$i]['email'] = $getdata[$i]['email'];
       				$result[$i]['password'] = $getdata[$i]['password'];
        			$result[$i]['country'] = $getdata[$i]['country'];
        			$result[$i]['organization'] = $getdata[$i]['organization'];
					if($getdata[$i]['subscribe'] == 0){
						$result[$i]['subscribe'] = 'false';
						} else {
						$result[$i]['subscribe'] = 'true';
						}
        			$result[$i]['datetime'] = $getdata[$i]['datetime'];
				}

				echo json_encode($result);

		}else{
			echo "[]";
		}
		
?>