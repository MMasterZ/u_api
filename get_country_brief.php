<?php 
require_once('connection.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];

$result_country = $db -> select("country_brief","*",[
"economy" => $exp_country,
"year" => $year,
]);

for ($i=0; $i < count($result_country); $i++) { 
$getData[$i]['economy'] = $result_country[$i]['economy'];
$getData[$i]['year'] = $result_country[$i]['year'];
$getData[$i]['set1'] = $result_country[$i]['set1'];
$getData[$i]['set2'] = $result_country[$i]['set2'];
$getData[$i]['set3'] = $result_country[$i]['set3'];
$getData[$i]['set4'] = $result_country[$i]['set4'];
$getData[$i]['set5'] = $result_country[$i]['set5'];
$getData[$i]['set6'] = $result_country[$i]['set6'];
$getData[$i]['set7'] = $result_country[$i]['set7'];
$getData[$i]['set8'] = $result_country[$i]['set8'];
$getData[$i]['set9'] = $result_country[$i]['set9'];
$getData[$i]['set9a'] = $result_country[$i]['set9a'];
$getData[$i]['set10'] = $result_country[$i]['set10'];
$getData[$i]['set10a'] = $result_country[$i]['set10a'];
$getData[$i]['set11'] = $result_country[$i]['set11'];
$getData[$i]['set11a'] = $result_country[$i]['set11a'];
$getData[$i]['set12'] = $result_country[$i]['set12'];
$getData[$i]['set12a'] = $result_country[$i]['set12a'];
}

echo json_encode($getData);

?>