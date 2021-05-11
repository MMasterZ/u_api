<?php
require_once('connection.php');

$exp_country = strtolower($_GET['exp_country']);
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;



//backward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where (variable = 'fva_yl' )  group by exp_sector order by sum DESC limit 10" ;
$backward = $db->query($sql)->fetchAll();


for($i=0;$i<count($backward);$i++){
    $sector = $backward[$i]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    //extra cal -a-1
    // $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT')  " ;
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT') and  ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export' ) and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
    $backward_b_1 = $db->query($sql)->fetchAll();

    $result[$i]['sector'] = $sector_short[0];
    $result[$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result[$i]['value'] = round($backward_b_1[0]['sum'],2);
}
usort($result, function($a, $b) {
    return $a['value'] < $b['value']? 1: -1;
});


//forward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  group by exp_sector order by sum DESC limit 10" ;
$backward = $db->query($sql)->fetchAll();


for($i=0;$i<count($backward);$i++){
    $sector = $backward[$i]['exp_sector'];
     $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    //extra cal -a-1
    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
    $backward_b_1 = $db->query($sql)->fetchAll();
    $result2[$i]['sector'] = $sector_short[0];
    $result2[$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result2[$i]['value'] = round($backward_b_1[0]['sum'],2);
    // $result[$i+5]['sector'] = $sector_short[0];
    // $result[$i+5]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    // $result[$i+5]['value'] = round($backward_b_1[0]['sum'],2);
 }
 usort($result2, function($a, $b) {
    return $a['value'] < $b['value']? 1: -1;
});

for($i=0;$i<5;$i++){
    $result[$i+5]['sector'] =$result2[$i]['sector'];
    $result[$i+5]['precent'] = $result2[$i]['precent'];
    $result[$i+5]['value'] = $result2[$i]['value'];
}
 echo json_encode($result);

?>