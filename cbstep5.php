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

uasort($backward, function($a, $b) {
    return  $b['sum'] - $a['sum'];
});


$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
$backward_a_2 = $db->query($sql)->fetchAll();
 
$count = 0;
for($i=0;$i<count($backward);$i++){
    $source_country = $backward[$i]['source_country'];
    if(checkCountry($source_country)){
        if($count <= 9){
        $result['backward'][$count]['country'] = $source_country;
        $result['backward'][$count]['precent'] = round($backward[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result['backward'][$count]['value'] = round($backward[$i]['sum'],2);
        $count++;
        }
    }
}


//forward
$sql  = "select sum(value) as sum, imp_country from " . $tableName . " where  (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  group by imp_country order by sum DESC " ;
$backward = $db->query($sql)->fetchAll();


$sql  = "select sum(value) as sum from " . $tableName . " where  (variable='total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
$backward_a_2 = $db->query($sql)->fetchAll();

$count = 0;
for($i=0;$i<count($backward);$i++){
    $imp_country = $backward[$i]['imp_country'];
    if($imp_country !='RoW'){
        $result['forward'][$count]['country'] = $imp_country;
        $result['forward'][$count]['precent'] = round($backward[$i]['sum']/$backward_a_2[0]['sum']*100,2);
        $result['forward'][$count]['value'] = round($backward[$i]['sum'],2);
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
// echo $set6;

// end;
 //Set 7
 $tableName = $exp_country . "_" . $year;
$result = array();

//ดึง ชื่อ Section ทั้งหมดออกมา
$sql = "select name,shortname from sector_data";
$sector_data = $db->query($sql)->fetchAll();
for($i=1;$i<count($sector_data);$i++){
    $sector =  $sector_data[$i][0];
     $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
    $backward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  " ;
    $backward_a_2 = $db->query($sql)->fetchAll();

    $sql  = "select sum(value) as sum from " . $tableName . " where  exp_sector = '" . $sector ."' and(variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')    and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
    $forward_a_1 = $db->query($sql)->fetchAll();

    //extra cal -a-2
    $sql  = "select sum(value) as sum from " . $tableName . " where exp_sector = '" . $sector ."' and (variable='total_export')   and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
    $forward_a_2 = $db->query($sql)->fetchAll();


    $result['backward'][$i-1]['sector']  = $sector_data[$i][1];
    if($backward_a_1[0]['sum'] !=0 ){
        $result['backward'][$i-1]['precent'] = round($backward_a_1[0]['sum']/$backward_a_2[0]['sum']*100,2);
         $result['backward'][$i-1]['value'] =  round($backward_a_1[0]['sum'],2);
    } else {
        $result['backward'][$i-1]['precent'] =0;
         $result['backward'][$i-1]['value'] = 0;
    }
    

    $result['forward'][$i-1]['sector']  = $sector_data[$i][1];
     $result['forward'][$i-1]['sector']  = $sector_data[$i][1];
    if($forward_a_1[0]['sum'] !=0 ){
        $result['forward'][$i-1]['precent'] = round($forward_a_1[0]['sum']/$forward_a_2[0]['sum']*100,2);
         $result['forward'][$i-1]['value'] =  round($forward_a_1[0]['sum'],2);
    } else {
        $result['forward'][$i-1]['precent'] =0;
         $result['forward'][$i-1]['value'] = 0;
    }
  
}


//  echo "\n**********7********\n";

 $dataInput = json_encode($result);
$set7 = $dataInput;
// echo $set7;

$db->update("country_brief",["set6"=>$set6,"set7"=>$set7],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>