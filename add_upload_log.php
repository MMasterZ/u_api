<?php 
require_once('connection.php');

$_POST = json_decode(file_get_contents("php://input"),true);

$country = $_POST['country'];
$datayear = $_POST['year'];
$currentdate = date("d/m/Y");

$db -> insert("upload_log",[
    "country" => $country,
    "data_year" => $datayear,
    "lastUpdate" => $currentdate
]);

echo "Success";

?>