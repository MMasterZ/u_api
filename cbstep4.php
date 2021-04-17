<?php
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];
// $exp_country = 'THA';
// $year = 2017;

//Set 4 
$tableName = $exp_country . "_" . $year;
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$v1=  $directly[0][0];

//find value1
$v2 = 0;
// //get country in same region
$country_data = $db->select("country_list","iso");
for($i=0; $i<count($country_data);$i++){
    if($country_data[$i] != $exp_country){
        $exp_country2 = $country_data[$i];
        $tableName2 = $exp_country2 . "_" . $year;
        $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DVA_FIN' or variable='DVA_INT') and imp_country='".  $exp_country ."'";
        $directly = $db->query($sql)->fetchAll();
        $v2 +=  $directly[0][0];
    }
    
}

//find value_gross
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export' )  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$vgross=  $directly[0][0];
// echo $vgross;

$vfinale = ($v1- $v2)/$vgross*100;
$result4['valueAddTradeBalance']['current'] = round($vfinale,1);

//***********for 2007 */ 
$tableName = $exp_country . "_2007";
//find value
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$v1=  $directly[0][0];

//find value1
$v2 = 0;
// //get country in same region
$country_data = $db->select("country_list","iso");
for($i=0; $i<count($country_data);$i++){
    if($country_data[$i] != $exp_country){
        $exp_country2 = $country_data[$i];
        $tableName2 = $exp_country2 . "_2007";
        $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DVA_FIN' or variable='DVA_INT') and imp_country='".  $exp_country ."'";
        $directly = $db->query($sql)->fetchAll();
        $v2 +=  $directly[0][0];
    }
    
}

//find value_gross
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export' )  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$vgross=  $directly[0][0];
// echo $vgross;

$vfinale = ($v1- $v2)/$vgross*100;
$result4['valueAddTradeBalance']['2007'] = round($vfinale,1);
// echo "\n**********4********\n";
// echo json_encode($result4);
$dataInput = json_encode($result4);
$set4 = $dataInput;

//Set 5
$tableName = $exp_country . "_" .$year;
//find value
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$v1=  $directly[0][0];


//find value1
$v2 = 0;
$country_data = $db->select("country_list","iso");
for($i=0; $i<count($country_data);$i++){
    if($country_data[$i] != $exp_country){
        $exp_country2 = $country_data[$i];
        $tableName2 = $exp_country2 . "_" . $year;
        $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'total_export') and imp_country='".  $exp_country ."'";
        $directly = $db->query($sql)->fetchAll();
        $v2 +=  $directly[0][0];
    }
    
}

//find value_gross
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export' )  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$vgross=  $directly[0][0];

$vfinale = ($v1- $v2)/$vgross*100;
$result5['grossTradeBalance']['current'] = round($vfinale,1);

//2007
$tableName = $exp_country . "_2007";
//find value
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$v1=  $directly[0][0];

;

//find value1
$v2 = 0;
$country_data = $db->select("country_list","iso");
for($i=0; $i<count($country_data);$i++){
    if($country_data[$i] != $exp_country){
        $exp_country2 = $country_data[$i];
        $tableName2 = $exp_country2 . "_2007";
        $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'total_export') and imp_country='".  $exp_country ."'";
        $directly = $db->query($sql)->fetchAll();
        $v2 +=  $directly[0][0];
    }
    
}



//find value_gross
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export' )  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$directly = $db->query($sql)->fetchAll();
$vgross=  $directly[0][0];

$vfinale = ($v1- $v2)/$vgross*100;
$result5['grossTradeBalance']['2007'] = round($vfinale,1);

// echo "\n**********5********\n";
// echo json_encode($result5);
$dataInput = json_encode($result5);
$set5 = $dataInput;
// $db->update("country_brief",["set5"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);


$db->update("country_brief",["set4"=>$set4,"set5"=>$set5],["AND"=>["economy"=>$exp_country,"year"=>$year]]);s
?>