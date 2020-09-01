<?php
require_once('connection.php');

$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;


//backward
$sql  = "select sum(value) as sum, imp_country from " . $tableName . " where exp_country='" . $exp_country. "' and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  group by imp_country order by sum DESC limit 5" ;
$backward = $db->query($sql)->fetchAll();




for($i=0;$i<count($backward);$i++){
    $imp_country = $backward[$i]['imp_country'];

    //extra cal -a-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_country='" . $exp_country. "' and year = " . $year . " and exp_sector = '" . $sector ."' and(variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3') " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_country='" . $exp_country. "' and year = " . $year . " and exp_sector = '" . $sector ."' and (variable='total_export') " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_country='" . $exp_country. "' and year = " . $year . " and exp_sector = '" . $sector ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  " ;
    $backward_b_1 = $db->query($sql)->fetchAll();

    //backwar-layer-2
    $sql  = "select sum(value) as sum,  exp_sector from " . $tableName . " where  imp_country = '" . $imp_country ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')   group by exp_sector order by sum DESC limit 5" ;
    echo $sql;
    $backward2 = $db->query($sql)->fetchAll();

    $result[$i]['imp_country'] = $backward[0]['imp_country'];
    $result[$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,0);
    $result[$i]['value'] = round($backward_b_1[0]['sum']/1000,0);
    $result[$i]['exp_sector1'] = $backward2[0]['exp_sector'];
    $result[$i]['val1'] = round($backward2[0]['sum']/1000,2);
    $result[$i]['exp_sector2'] = $backward2[1]['exp_sector'];
    $result[$i]['val2'] = round($backward2[1]['sum']/1000,2);
    $result[$i]['exp_sector3'] = $backward2[2]['exp_sector'];
    $result[$i]['val3'] = round($backward2[2]['sum']/1000,2);
    $result[$i]['exp_sector4'] = $backward2[3]['exp_sector'];
    $result[$i]['val4'] = round($backward2[3]['sum']/1000,2);
    $result[$i]['exp_sector5'] = $backward2[4]['exp_sector'];
    $result[$i]['val5'] = round($backward2[4]['sum']/1000,2);
}

 echo json_encode($result);

?>