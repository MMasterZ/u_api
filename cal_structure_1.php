<?php
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];
$table_name = $exp_country . "_" . $year;

if($sector == 0){
$sql = "select distinct
(select sum(value) from ". $table_name." where (variable = 'DVA_FIN' or variable = 'DVA_INT') and imp_country = '".$imp_country."') as imp_cons, 
(select sum(value) from ".$table_name . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3') and imp_country = '".$imp_country."') as imp_exp,
(select sum(value) from ".$table_name . " where (variable = 'RDV_FIN1' or variable = 'RDV_FIN2' or variable = 'RDV_INT') and imp_country = '".$imp_country."') as dom_cons,
(select sum(value) from ".$table_name . " where (variable = 'DDC_FIN' or variable = 'DDC_INT' or variable = 'MDC' or variable = 'ODC') and imp_country = '".$imp_country."') as doublex,
(select sum(value) from ".$table_name . " where (variable = 'MVA_FIN' or variable = 'MVA_INT' or variable = 'OVA_FIN' or variable = 'OVA_INT') and imp_country = '".$imp_country."') as imp_cont,
(select sum(value) from ".$table_name . " where (variable = 'total_export') and imp_country = '".$imp_country."') as import_country,
(select sum(value) from ".$table_name . " where (variable = 'total_export') ) as import_world
from ".$table_name;
} else {
  $sql = "select distinct
(select sum(value) from ". $table_name." where (variable = 'DVA_FIN' or variable = 'DVA_INT') and exp_sector = '" . $sector_data[$sector] . "' and imp_country = '".$imp_country."') as imp_cons, 
(select sum(value) from ".$table_name . " where (variable = 'DVA_INTrex1' or variable = 'DVA_INTrex2' or variable = 'DVA_INTrex3') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as imp_exp,
(select sum(value) from ".$table_name . " where (variable = 'RDV_FIN1' or variable = 'RDV_FIN2' or variable = 'RDV_INT')  and exp_sector = '" . $sector_data[$sector] . "' and imp_country = '".$imp_country."') as dom_cons,
(select sum(value) from ".$table_name . " where (variable = 'DDC_FIN' or variable = 'DDC_INT' or variable = 'MDC' or variable = 'ODC') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as doublex,
(select sum(value) from ".$table_name . " where (variable = 'MVA_FIN' or variable = 'MVA_INT' or variable = 'OVA_FIN' or variable = 'OVA_INT') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as imp_cont,
(select sum(value) from ".$table_name . " where (variable = 'total_export') and exp_sector = '" . $sector_data[$sector] . "'  and imp_country = '".$imp_country."') as import_country,
(select sum(value) from ".$table_name . " where (variable = 'total_export') and exp_sector = '" . $sector_data[$sector] . "'  ) as import_world
from ".$table_name;
}

$value2 = $db->query($sql)->fetchAll();
$result['imp_cons'] = round($value2[0]['imp_cons'],2);
$result['imp_exp'] = round($value2[0]['imp_exp'],2);
$result['dom_cons'] = round($value2[0]['dom_cons'],2);
$result['double'] = round($value2[0]['doublex'],2);
$result['imp_cont'] = round($value2[0]['imp_cont'],2);
if($value2[0]['import_country'] > 1000){
  $t1 = round($value2[0]['import_country'] /1000,2) . "B";
} else {
  $t1 = round($value2[0]['import_country'],2) . "M";
}
$result['text_export_to_import_country'] = $t1;
if($value2[0]['import_world'] > 1000){
  $t1 = round($value2[0]['import_world'] /1000,2) . "B";
} else {
  $t1 = round($value2[0]['import_world'],2) . "M";
}
$result['text_export_to_world'] =$t1;
echo json_encode($result);
?>
