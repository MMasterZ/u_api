<?php
require_once('connection.php');
require_once('main_function.php');
$exp_country = strtolower($_GET['exp_country']);
$year = $_GET['year'];
$tableName = $exp_country . "_" . $year;


//backward
$sql  = "select sum(value) as sum, source_country from " . $tableName . " where  (variable = 'fva_yl' )  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by source_country order by sum DESC " ;
$backward = $db->query($sql)->fetchAll();


//extra cal -a-1
$sql  = "select sum(value) as sum from " . $tableName . " where  (variable = 'MVA_FIN' or variable='fva_yl')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by source_country order by sum DESC"  ;
$backward_a_1 = $db->query($sql)->fetchAll();


$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
$backward_a_2 = $db->query($sql)->fetchAll();
 
$count = 0;
for($i=0;$i<count($backward);$i++){
    $source_country = $backward[$i]['source_country'];
    if(checkCountry($source_country)){
        if($count <= 4){
        $result[$count]['country'] = $source_country;
        $result[$count]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result[$count]['value'] = round($backward_a_1[$i]['sum'],2);
        $count++;
        }
    }
}

//forward
$sql  = "select sum(value) as sum, imp_country from " . $tableName . " where  (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3'  or variable='RDV_FIN1'  or variable='RDV_FIN2'   or variable='RDV_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by imp_country order by sum DESC" ;
$backward = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum from " . $tableName . " where ( variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3'  or variable='RDV_FIN1'  or variable='RDV_FIN2'   or variable='RDV_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) group by imp_country order by sum DESC " ;
$backward_a_1 = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
$backward_a_2 = $db->query($sql)->fetchAll();

$count = 5;
for($i=0;$i<count($backward);$i++){
    $imp_country = $backward[$i]['imp_country'];
    if(checkCountry($imp_country)){
        if($count <= 9){
        $result[$count]['country'] = $imp_country;
        $result[$count]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result[$count]['value'] = round($backward_a_1[$i]['sum'],2);
        $count++;
        }
    }
}
 echo json_encode($result);

?>