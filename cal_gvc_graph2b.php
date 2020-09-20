<?php
require_once('connection.php');
require_once('sector_data.php');
$data = json_decode(file_get_contents("php://input"), true);
$exp_country= $data['exporting'];
$source_country = $data['sourcing'];
$year = $data['year'];

$exp_country = 'THA';
$source_country = 'CHN';
$year = 2017;

$tableName = $exp_country . "_" . $year;

 $sql  = "select sum(value) as sum,  exp_sector from " . $tableName . " where  imp_country = '" . $source_country ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')   group by exp_sector order by sum DESC limit 5" ;
 $backward2 = $db->query($sql)->fetchAll();

 for($i=0;$i<=4;$i++){
    $sector = $backward2[$i]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['sector'] = $sector_short[0];
    $result[$i]['val'] = round($backward2[$i]['sum'],2);
 }
   
 echo json_encode($result);

?>