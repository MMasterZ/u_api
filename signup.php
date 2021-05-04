<?php
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);

$email = $_POST['email'];
$password = $_POST['password'];
$country = $_POST['country'];
$organization = $_POST['organization'];
$is_validation = $_POST['isValidation'];
$is_subscribe = $_POST['isSubscribe'];




$checkExists = $db -> count("user_account",
[
    "email" => $email
]);

if($checkExists)
{
    // Email is found in database
    echo "0";
}else{
    // email not found can be sign up
   $db -> insert("user_account",[
        "email" => $email,
        "password" => $password,
        "country" => $country,
        "organization" => $organization,
        "is_validation" => "0",
        "is_subscribe" => $is_subscribe,
        "query" => ""
    ]);

    $headers = "From: " . 'no-reply' . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    $lastest_id = $db -> id();

    $validationEmail = "https://riva.negotiatetrade.org/#/validation/" . $lastest_id;

    
    $message = "<p><strong>Email Confirmation</strong></p>
    <p>You're almost ready to start.<p>Simply click the link below to verify your email address.</p></p><br><a href='" . $validationEmail ."'>Verify email address</a><p>If you cannot open link</p><p>You need to move this mail to inbox folder to open the links directly.</p>";

    if($lastest_id)
    {
        mail($email,"Email Verification",$message,$headers);
    }

    

    echo $lastest_id;
}

 ?>