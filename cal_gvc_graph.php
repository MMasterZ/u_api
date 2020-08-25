<?php
require_once('connection.php');

$country = $_GET['country'];
$year = $_GET['year'];

$sql  = "select sum(value) as sum, exp_sector from country_data 
where exp_country='" . $country. "' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl')  group by exp_sector order by sum DESC limit 5" ;
$backward = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum, exp_sector from country_data 
where exp_country='" . $country. "' and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2'  or variable='DVA_INTrex3')  group by exp_sector order by sum DESC limit 5" ;
$forward = $db->query($sql)->fetchAll();


for($i=0;$i<=4;$i++){

$result[$i]['b_sector']['value']  = round($backward[$i]['sum']/100);
$result[$i]['b_sector']['name']= $backward[$i]['exp_sector'];

$result[$i]['f_sector']['value']  = round($forward[$i]['sum']/100);
$result[$i]['f_sector']['name']= $forward[$i]['exp_sector'];
}
 echo json_encode($result);

?>