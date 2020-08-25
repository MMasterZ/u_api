<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

		$getdata = $db ->delete('account',
			[ "AND" => [
					"id" => $_POST
				]
			]
		);

		$checkdb = $db -> count("account","*");

		if ($checkdb) {

			$getdata = $db ->select('account','*');
			
				for($i=0;$i<count($getdata);$i++){
					$result[$i]['id'] = $getdata[$i]['id'];
					$result[$i]['username'] = $getdata[$i]['username'];
					$result[$i]['email'] = $getdata[$i]['email'];
                    $result[$i]['password'] = $getdata[$i]['password'];
                    $result[$i]['status'] = $getdata[$i]['status'];
				}

				echo json_encode($result);

		}else{
			echo "[]";
		}
		
?>