<?php
require_once('connection.php');
require_once('main_function.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];
// $exp_country = 'THA';
// $year = 2017;

//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
// $country_data = $db->select("country_list","iso",[
// region =>$region
// ]);
$country_data = $db->select("country_list","*");

for($i=0; $i<count($country_data);$i++){
    $exp_country2 = $country_data[$i]['iso'];
    $tableName2 = $exp_country2 . "_" . $year;
    $result[$exp_country2]['region'] = $country_data[$i]['region'];
    $result[$exp_country2]['area'] = $country_data[$i]['area'];
    //Used in export production
  $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld')) " ;
  $value1 = $db->query($sql)->fetchAll();
  $result[$exp_country2]['usedInExportProduction']['total'] = round($value1[0][0],2);


  //Imported content
  $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
  $value2 = $db->query($sql)->fetchAll();
  $result[$exp_country2]['importedContent']['total'] = round($value2[0][0],2);


  //Double counted
  $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC' or variable='ODC') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
  $value3 = $db->query($sql)->fetchAll();
  $result[$exp_country2]['doubleCounted']['total'] = round($value3[0][0],2);

  //total sum of three
  $total =  round($value1[0][0],2) + round($value2[0][0],2)+ round($value3[0][0],2);
  $result[$exp_country2]['sum']['total'] = round($total,2);

  //find ratio
    $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where variable = 'total_export'  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))" ;
  $totalAll = $db->query($sql)->fetchAll();
 $result[$exp_country2]['usedInExportProduction']['ratio'] = round($value1[0][0]/$totalAll[0][0]*100,2);
  $result[$exp_country2]['importedContent']['ratio'] = round($value2[0][0]/$totalAll[0][0]*100,2);
  $result[$exp_country2]['doubleCounted']['ratio'] = round($value3[0][0]/$totalAll[0][0]*100,2);
   $result[$exp_country2]['sum']['ratio'] =round(round($value1[0][0]/$totalAll[0][0]*100,2) + round($value2[0][0]/$totalAll[0][0]*100,2) + round($value3[0][0]/$totalAll[0][0]*100,2),2);

}


  $dataInput = json_encode($result);
$db->update("country_brief",["set8"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>
