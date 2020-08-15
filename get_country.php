<?php 
require_once('connection.php');

$country_list = $db -> select("country_list","*");

for ($i=0; $i <count($country_list) ; $i++) { 
    $result[$i]['id'] = $country_list[$i]['id'];
    $result[$i]['name'] = $country_list[$i]['name'];
    $result[$i]['region'] = $country_list[$i]['region'];
    $result[$i]['iso'] = $country_list[$i]['iso'];
}

echo json_encode($result);

?>