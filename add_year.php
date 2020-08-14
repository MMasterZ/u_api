<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

    // POST TYPE : YEAR 
    if($_POST['type'] == 'YEAR'){
      $check = $db -> select("year","year",[
            "year" => $_POST['year']
        ]);

        if(empty($check)){
            $db -> insert('year',[
                "year" => $_POST['year'],
                "status" => 1,
            ]);
            echo "Success";
        }else{
            echo "Error Dulpicate Data";
        }
    }

?>