<?php 
require_once('connection.php');

$_POST = json_decode(file_get_contents("php://input"),true);

$checkdb = $db -> count("account","*",[
	"username" => $_POST['username'],
	"password" => $_POST['password'],
]);

if($checkdb){

	$getdata = $db -> select('account','*',[
		"username" => $_POST['username'],
		"password" => $_POST['password'],
	]);

	$result['id'] = $getdata[0]['id'];
	$result['username'] = $getdata[0]['username'];
	$result['email'] = $getdata[0]['email'];
	$result['status'] = $getdata[0]['status'];
	$result['password'] = $getdata[0]['password'];
	
	echo json_encode($result);

}else{

	echo "Login Failed";

}

?>