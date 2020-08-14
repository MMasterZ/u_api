<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

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

?>