<?php
require_once('connection.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;


//backward
$sql  = "select sum(value) as sum, source_country from " . $tableName . " where  (variable = 'fva_yl')  group by source_country order by sum DESC limit 5" ;
$backward = $db->query($sql)->fetchAll();


//extra cal -a-1
$sql  = "select sum(value) as sum from " . $tableName . " where  (variable = 'MVA_FIN' or variable='fva_yl')  group by source_country order by sum DESC limit 5"  ;
$backward_a_1 = $db->query($sql)->fetchAll();


$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export') " ;
$backward_a_2 = $db->query($sql)->fetchAll();
 

for($i=0;$i<count($backward);$i++){
    $source_country = $backward[$i]['source_country'];
 

    //backwar-layer-2
    $sql  = "select sum(value) as sum,  exp_sector from " . $tableName . " where  source_country = '" . $source_country ."' and (variable = 'fva_yl' )  group by exp_sector order by sum DESC limit 5" ;
    $backward2 = $db->query($sql)->fetchAll();

    $result[$i]['a'] = $source_country;
    $result[$i]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
    $result[$i]['value'] = round($backward_a_1[$i]['sum']/1000,2);
    $sector = $backward2[0]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['b1'] = $sector_short[0];
    $result[$i]['val1'] = round($backward2[0]['sum']/1000,2);
    $sector = $backward2[1]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['b2'] = $sector_short[0];
    $result[$i]['val2'] = round($backward2[1]['sum']/1000,2);
    $sector = $backward2[2]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['b3'] = $sector_short[0];
    $result[$i]['val3'] = round($backward2[2]['sum']/1000,2);
    $sector = $backward2[3]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['b4'] = $sector_short[0];
    $result[$i]['val4'] = round($backward2[3]['sum']/1000,2);
    $sector = $backward2[4]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    $result[$i]['b5'] = $sector_short[0];
    $result[$i]['val5'] = round($backward2[4]['sum']/1000,2);
}

 echo json_encode($result);

?>