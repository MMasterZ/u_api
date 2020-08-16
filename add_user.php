<?php 
require_once('connection.php');

 $_POST = json_decode(file_get_contents("php://input"),true);

    $check = $db -> select("account","email",[
        "email" => $_POST['email']
    ]);

    if(empty($check)){
        $db -> insert('account',[
            "username" => $_POST['username'],
            "email" => $_POST['email'],
            "password" => $_POST['password']
        ]);
        

         $getdata = $db ->select('account','*');
            
        for($i=0;$i<count($getdata);$i++){
            $result[$i]['id'] = $getdata[$i]['id'];
            $result[$i]['username'] = $getdata[$i]['username'];
            $result[$i]['email'] = $getdata[$i]['email'];
            $result[$i]['password'] = $getdata[$i]['password'];
        }

        echo json_encode($result);

    }else{
        echo "Error Dulpicate Email";
    }

?>