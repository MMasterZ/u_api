<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

   
	$check = $db -> select("year","year",[
				"year" => $_POST['year']
		]);

		if(empty($check)){
				$db -> insert('year',[
						"year" => $_POST['year'],
						"status" => 1,
				]);

				$checkdb = $db -> count("year","*");

				if($checkdb){   
					$getdata = $db ->select('year','*');
				
					for($i=0;$i<count($getdata);$i++){
							$result[$i]['year'] = $getdata[$i]['year'];
							$result[$i]['status'] = $getdata[$i]['status'];
					}

					echo json_encode($result);
				}else{
						echo "[]";
				}
				
		}else{
				echo "Error Dulpicate Data";
		}

?>