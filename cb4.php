<?php
//*************Value-added trade balance *************/
//****Current year *********/
//find value
$tableName = $exp_country . "_" . $year;
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT')";
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
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export' )";
$directly = $db->query($sql)->fetchAll();
$vgross=  $directly[0][0];
// echo $vgross;

$vfinale = ($v1- $v2)/$vgross*100;
$result4['valueAddTradeBalance']['current'] = round($vfinale,1);

//***********for 2007 */ 
$tableName = $exp_country . "_2007";
//find value
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT')";
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
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'total_export' )";
$directly = $db->query($sql)->fetchAll();
$vgross=  $directly[0][0];
// echo $vgross;

$vfinale = ($v1- $v2)/$vgross*100;
$result4['valueAddTradeBalance']['2007'] = round($vfinale,1);
echo "\n**********4********\n";
echo json_encode($result4);
$dataInput = json_encode($result4);
$db->update("country_brief",["set4"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);


?>