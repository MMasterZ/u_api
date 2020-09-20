<?php
require_once('connection.php');
require_once('sector_data.php');


$exp_country = $_GET['exp_country'];
$imp_country = $_GET['imp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];


//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);
$count = 0;
for($i=0; $i<count($country_data);$i++){
  $exp_country2 = $country_data[$i];
 
 
  if($exp_country2 != $imp_country){
    
     $area = $db->select("country_list","name",["iso"=>$exp_country2]);
     $result[$count]['imp_country'] =$area[0];
  $table_name = $exp_country2 . "_" . $year;

if($sector == 0){
$sql = "select distinct
(select sum(value) from ". $table_name." where (variable = 'DVA_FIN' or variable = 'DVA_INT') and imp_country = '".$imp_country."') as imp_cons, 
(select sum(value) from ".$table_name . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3') and imp_country = '".$imp_country."') as imp_exp,
(select sum(value) from ".$table_name . " where (variable = 'RDV_FIN1' or variable = 'RDV_FIN2' or variable = 'RDV_INT') and imp_country = '".$imp_country."') as dom_cons,
(select sum(value) from ".$table_name . " where (variable = 'DDC_FIN' or variable = 'DDC_INT' or variable = 'MDC' or variable = 'ODC') and imp_country = '".$imp_country."') as doublex,
(select sum(value) from ".$table_name . " where (variable = 'MVA_FIN' or variable = 'MVA_INT' or variable = 'OVA_FIN' or variable = 'OVA_INT') and imp_country = '".$imp_country."') as imp_cont
from ".$table_name;
} else {
  $sql = "select distinct
(select sum(value) from ". $table_name." where (variable = 'DVA_FIN' or variable = 'DVA_INT') and exp_sector = '" . $sector_data[$sector] . "' and imp_country = '".$imp_country."') as imp_cons, 
(select sum(value) from ".$table_name . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as imp_exp,
(select sum(value) from ".$table_name . " where (variable = 'RDV_FIN1' or variable = 'RDV_FIN2' or variable = 'RDV_INT')  and exp_sector = '" . $sector_data[$sector] . "' and imp_country = '".$imp_country."') as dom_cons,
(select sum(value) from ".$table_name . " where (variable = 'DDC_FIN' or variable = 'DDC_INT' or variable = 'MDC' or variable = 'ODC') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as doublex,
(select sum(value) from ".$table_name . " where (variable = 'MVA_FIN' or variable = 'MVA_INT' or variable = 'OVA_FIN' or variable = 'OVA_INT') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as imp_cont
from ".$table_name;
}
$value2x = $db->query($sql)->fetchAll();
$value1 = round($value2x[0]['imp_cons'],2);
$value2 = round($value2x[0]['imp_exp'],2);
$value3 = round($value2x[0]['dom_cons'],2);
$value4 = round($value2x[0]['doublex'],2);
$value5 = round($value2x[0]['imp_cont'],2);

$total = round($value1,2) + round($value2,2) + round($value3,2) + round($value4,2) + round($value5,2);
$result[$count]['imp_cons'] = round(round($value1,2)/$total*100,2);
$result[$count]['imp_exp'] = round(round($value2,2)/$total*100,2);
$result[$count]['dom_cons'] = round(round($value3,2)/$total*100,2);
$result[$count]['double'] = round(round($value4,2)/$total*100,2);
$result[$count]['imp_cont'] = round(round($value5,2)/$total*100,2);
$count +=1;
  }
}

echo json_encode($result);
?>
