<?php
require_once('connection.php');
require_once('main_function.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];
// $exp_country = 'THA';
// $year = 2017;

// set6
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
        if($count <= 9){
        $result['backward'][$count]['country'] = $source_country;
        $result['backward'][$count]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result['backward'][$count]['value'] = round($backward_a_1[$i]['sum'],2);
        $count++;
        }
    }
}

//forward
$sql  = "select sum(value) as sum, imp_country from " . $tableName . " where  (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by imp_country order by sum DESC " ;
$backward = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum from " . $tableName . " where ( variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by imp_country order by sum DESC " ;
$backward_a_1 = $db->query($sql)->fetchAll();

$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
$backward_a_2 = $db->query($sql)->fetchAll();

$count = 0;
for($i=0;$i<count($backward);$i++){
    $imp_country = $backward[$i]['imp_country'];
    if($imp_country !='RoW'){
        $result['forward'][$count]['country'] = $imp_country;
        $result['forward'][$count]['precent'] = round($backward_a_1[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result['forward'][$count]['value'] = round($backward_a_1[$i]['sum'],2);
        $count++;
    }
    if($count == 10){
        break;
    }
    

}
// echo "\n**********6********\n";
//  echo json_encode($result);
 $dataInput = json_encode($result);
 $set6 = $dataInput;


 //Set 7
 $tableName = $exp_country . "_" . $year;
$result = array();

//backward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where (variable = 'fva_yl')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))   group by exp_sector order by sum DESC limit 10" ;
$backward = $db->query($sql)->fetchAll();

for($i=0;$i<count($backward);$i++){
    $sector = $backward[$i]['exp_sector'];
    $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    //extra cal -a-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
    $backward_b_1 = $db->query($sql)->fetchAll();

    $result['backward'][$i]['sector'] = $sector_short[0];
    $result['backward'][$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result['backward'][$i]['value'] = round($backward_b_1[0]['sum'],2);
}


//forward
$sql  = "select sum(value) as sum, exp_sector from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')    and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) group by exp_sector order by sum DESC limit 10" ;
$backward = $db->query($sql)->fetchAll();


for($i=0;$i<count($backward);$i++){
    $sector = $backward[$i]['exp_sector'];
     $sector_short = $db->select("sector_data","shortname",["name"=>$sector]);
    //extra cal -a-1
    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and(variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')    and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    //extra cal -b-1
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')    and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
    $backward_b_1 = $db->query($sql)->fetchAll();

    $result['forward'][$i]['sector'] = $sector_short[0];
    $result['forward'][$i]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
    $result['forward'][$i]['value'] = round($backward_b_1[0]['sum'],2);
 }
//  echo "\n**********7********\n";
//  echo json_encode($result);
 $dataInput = json_encode($result);
$set7 = $dataInput;

$db->update("country_brief",["set6"=>$set6,"set7"=>$set7],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>