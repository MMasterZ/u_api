<?php
require_once('connection.php');
require_once('sector_data.php');
require_once('country_list.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];

$table_name = strtolower($exp_country) . "_" . $year;
$table2_name = strtolower($imp_country) . "_" . $year;

// Forward linkage
//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
if(count($region_data)==0){
  $country_data = country_list($exp_country);
} else {
  $region = $region_data[0];
  //get country in same region
  $country_data = $db->select("country_list","iso",[
  region =>$region
  ]);
}

$count = 0;
for($i=0; $i<count($country_data);$i++){
  $exp_country2 = $country_data[$i];
  if($exp_country2 != $imp_country){


$area = $db->select("country_list","name",["iso"=>$exp_country2]);
  $result[$count]['country'] =$area[0];
  $table3_name = strtolower($exp_country2) . "_" . $year;
  
  //Forward
  if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' ) " ;
  $value1 = $db->query($sql)->fetchAll();

    $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "' and (variable = 'total_export' ) " ;
  $value2 = $db->query($sql)->fetchAll();

  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' ) " ;

  $value1 = $db->query($sql)->fetchAll(); 
  

   $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and (variable = 'total_export' ) " ;

  $value2 = $db->query($sql)->fetchAll(); 
  
  }
  
  $result[$count]['forward_v'] = round($value1[0]['sum'],2);
  $result[$count]['forward'] = round($value1[0]['sum']/$value2[0]['sum']*100,2);

  //Backward
    if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT' ) " ;
  $value3 = $db->query($sql)->fetchAll();


  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT' ) " ;
  $value3 = $db->query($sql)->fetchAll(); 
  }
 
  $result[$count]['backward_v'] = round($value3[0]['sum'],2);
  $result[$count]['backward'] = round($value3[0]['sum']/$value2[0]['sum']*100,2);


  
  //Double
    if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "' and (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC' or variable='ODC' ) " ;
  $value4 = $db->query($sql)->fetchAll();


  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC' or variable='ODC' ) " ;
  $value4 = $db->query($sql)->fetchAll(); 
  }

 
   $result[$count]['double_v'] = round($value4[0]['sum'],2);
  $result[$count]['double'] = round($value4[0]['sum']/$value2[0]['sum']*100,2);

  //final
  if($sector == 0){
  $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "' and (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT' ) " ;
  $value5 = $db->query($sql)->fetchAll();


  } else {

     $sql  = "select sum(value) as sum  from " . $table3_name . "  
  where imp_country='" . $imp_country. "'and exp_sector = '" . $sector_data[$sector] . "' and (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT'  ) " ;
  $value5 = $db->query($sql)->fetchAll(); 
  }
    $result[$count]['final_v'] = round($value5[0]['sum'],2);
  $result[$count]['final'] = round($value5[0]['sum']/$value2[0]['sum']*100,2);

  $result[$count]['totalGVC'] = round(($value4[0]['sum'] + $value5[0]['sum'] + $value1[0]['sum'] +$value3[0]['sum'])/$value2[0]['sum']*100,2);


  $count++;
    }
}


echo json_encode($result);
?>
