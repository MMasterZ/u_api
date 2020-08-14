<?php 
require_once('connection.php');

// SECTION : POST
if($_SERVER['REQUEST_METHOD'] == "POST"){

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

    // POST TYPE : USER
    if($_POST['type'] == 'USER'){
        $check = $db -> select("account","email",[
            "email" => $_POST['email']
        ]);

        if(empty($check)){
            $db -> insert('account',[
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "password" => $_POST['password']
            ]);
            echo "Success";
        }else{
            echo "Error Dulpicate Data";
        }
    }
}


// SECTION : RESULT


?>