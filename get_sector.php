<?php 
require_once('connection.php');

$country_list = $db -> select("sector","*");

for ($i=0; $i <count($country_list) ; $i++) { 
    $result[$i]['id'] = $country_list[$i]['id'];
    $result[$i]['name'] = $country_list[$i]['name'];
}

echo json_encode($result);

?>