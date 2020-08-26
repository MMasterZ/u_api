<?php
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];

$table_name = $exp_country . "_" . $year;
$table2_name = $imp_country . "_" . $year;

// Forward linkage
//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);


for($i=0; $i<count($country_data);$i++){
  $exp_country2 = $country_data[$i];
  $table3_name = $exp_country2 . "_" . $year;
  
  //Forward
  if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'  and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' ) " ;
  $value1 = $db->query($sql)->fetchAll();

    $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'  and year = " . $year ." and (variable = 'total_export' ) " ;
  $value2 = $db->query($sql)->fetchAll();

  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "'  and year = " . $year ." and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' ) " ;

  $value1 = $db->query($sql)->fetchAll(); 
  

   $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "'  and year = " . $year ." and (variable = 'total_export' ) " ;

  $value2 = $db->query($sql)->fetchAll(); 
  
  }
  $result[$i]['country'] = $exp_country2;
  $result[$i]['forward'] = round($value1[0]['sum']/$value2[0]['sum']*100,2);

  //Backward
    if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'  and year = " . $year ." and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT' ) " ;
  $value3 = $db->query($sql)->fetchAll();


  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "'  and year = " . $year ." and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT' ) " ;
  $value3 = $db->query($sql)->fetchAll(); 
  }
 
  $result[$i]['backward'] = round($value3[0]['sum']/$value2[0]['sum']*100,2);


  
  //Double
    if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'  and year = " . $year ." and (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC' or variable='ODC' ) " ;
  $value4 = $db->query($sql)->fetchAll();


  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where exp_country='" . $exp_country2. "' and imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "'  and year = " . $year ." and (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC' or variable='ODC' ) " ;
  $value4 = $db->query($sql)->fetchAll(); 
  }
 
  $result[$i]['double'] = round($value4[0]['sum']/$value2[0]['sum']*100,2);
}


echo json_encode($result);
?>
