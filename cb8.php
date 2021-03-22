<?php
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
  where variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3' " ;
  $value1 = $db->query($sql)->fetchAll();
  $result[$exp_country2]['usedInExportProduction']['total'] = round($value1[0][0],2);


  //Imported content
  $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN' or variable='OVA_INT' " ;
  $value2 = $db->query($sql)->fetchAll();
  $result[$exp_country2]['importedContent']['total'] = round($value2[0][0],2);


  //Double counted
  $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC' or variable='ODC' " ;
  $value3 = $db->query($sql)->fetchAll();
  $result[$exp_country2]['doubleCounted']['total'] = round($value3[0][0],2);

  //total sum of three
  $total =  round($value1[0][0],2) + round($value2[0][0],2)+ round($value3[0][0],2);
  $result[$exp_country2]['sum']['total'] = round($total,2);

  //find ratio
    $sql  = "select sum(value) as sum  from " . $tableName2 . "  
  where variable = 'total_export'  " ;
  $totalAll = $db->query($sql)->fetchAll();
 $result[$exp_country2]['usedInExportProduction']['ratio'] = round($value1[0][0]/$totalAll[0][0]*100,2);
  $result[$exp_country2]['importedContent']['ratio'] = round($value2[0][0]/$totalAll[0][0]*100,2);
  $result[$exp_country2]['doubleCounted']['ratio'] = round($value3[0][0]/$totalAll[0][0]*100,2);
   $result[$exp_country2]['sum']['ratio'] =round(round($value1[0][0]/$totalAll[0][0]*100,2) + round($value2[0][0]/$totalAll[0][0]*100,2) + round($value3[0][0]/$totalAll[0][0]*100,2),2);

}

 echo "\n**********8********\n";
 echo json_encode($result);
  $dataInput = json_encode($result);
$db->update("country_brief",["set8"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>
