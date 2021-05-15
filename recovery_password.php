<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$email = $_POST['email'];


$query = $db -> select("user_account","*",
[
    "email" => $email   
]);


if(count($query) > 0){

    
    $headers = "From: " . 'no-reply' . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";


   
    
    $message = "<p>Your password is :" . $query[0]['password']  . "  </p>";

    
        mail($email,"Password Recovery",$message,$headers);
    echo "success";


}else{
    echo "email not found";
}

?>