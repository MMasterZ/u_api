<?php

$tableName = $exp_country . "_" . $year;


//backward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where (variable = 'fva_fin_yl' or variable='fva_int_yl')  group by exp_sector order by sum DESC limit 10" ;
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

    $result['backward'][$i]['sector'] = $sector_short[0];
    $result['backward'][$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result['backward'][$i]['value'] = round($backward_b_1[0]['sum'],2);
}


//forward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  group by exp_sector order by sum DESC limit 10" ;
$backward = $db->query($sql)->fetchAll();


for($i=0;$i<count($backward);$i++){
    $sector = $backward[$i]['exp_sector'];
     $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    //extra cal -a-1
    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and(variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3') " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export') " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  " ;
    $backward_b_1 = $db->query($sql)->fetchAll();

    $result['forward'][$i]['sector'] = $sector_short[0];
    $result['forward'][$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result['forward'][$i]['value'] = round($backward_b_1[0]['sum'],2);
 }
 echo "\n**********7********\n";
 echo json_encode($result);

?>