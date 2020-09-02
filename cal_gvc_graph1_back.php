<?php
require_once('connection.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;


//backward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where exp_country='" . $exp_country. "' and year = " . $year ." and (variable = 'fva_fin_yl' or variable='fva_int_yl')  group by exp_sector order by sum DESC limit 5" ;
$backward = $db->query($sql)->fetchAll();


for($i=0;$i<count($backward);$i++){
    $sector = $backward[$i]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    //extra cal -a-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT') " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export') " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT') " ;
    $backward_b_1 = $db->query($sql)->fetchAll();

    //backwar-layer-2
    $sql  = "select sum(value) as sum,  source_country from " . $tableName . " where  exp_sector = '" . $sector ."' and (variable = 'fva_fin_yl' or variable='fva_int_yl')  group by source_country order by sum DESC limit 5" ;
    $backward2 = $db->query($sql)->fetchAll();

    $result[$i]['sector'] = $sector_short[0];
    $result[$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result[$i]['value'] = round($backward_b_1[0]['sum']/1000,2);
    $result[$i]['exp_country1'] = $backward2[0]['source_country'];
    $result[$i]['val1'] = round($backward2[0]['sum']/1000,2);
    $result[$i]['exp_country2'] = $backward2[1]['source_country'];
    $result[$i]['val2'] = round($backward2[1]['sum']/1000,2);
    $result[$i]['exp_country3'] = $backward2[2]['source_country'];
    $result[$i]['val3'] = round($backward2[2]['sum']/1000,2);
    $result[$i]['exp_country4'] = $backward2[3]['source_country'];
    $result[$i]['val4'] = round($backward2[3]['sum']/1000,2);
    $result[$i]['exp_country5'] = $backward2[4]['source_country'];
    $result[$i]['val5'] = round($backward2[4]['sum']/1000,2);
}

 echo json_encode($result);

?>