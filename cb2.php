<?php
//Find country in same group
//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);
$count = 0;
// print_r($country_data);
$directly2=0;
$double2 = 0;
$imported2 = 0;
$domestic2 = 0;
$export2 = 0;
for($i=0; $i<count($country_data);$i++){
    $exp_country2 = $country_data[$i];
    $tableName2 = $exp_country2 . "_" . $year;

    //Directly consumed
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DVA_FIN' or variable='DVA_INT')";
    $directly = $db->query($sql)->fetchAll();
    $directly2 = $directly2 + $directly[0][0];

    //Double
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC'  or variable='ODC')";
    $double = $db->query($sql)->fetchAll();
    $double2 = $double2 + $double[0][0];

    //Imported content
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN'  or variable='OVA_INT')";
    $importedContent = $db->query($sql)->fetchAll();
    $imported2 = $imported2 + $importedContent[0][0];

    //Domestic consumption
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT')";
    $domestic = $db->query($sql)->fetchAll();
    $domestic2 = $domestic2 + $domestic[0][0];

    //Used in export production
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')";
    $usedInExport = $db->query($sql)->fetchAll();
    $export2 = $export2 + $usedInExport[0][0];

}

$total = round($directly2,1) + round($double2,1) + round($imported2,1) + round($domestic2,1) + round($export2,1) ;


$result2['directlyConsumed']['total']  = round($directly2,1) ;
$result2['directlyConsumed']['ratio']  = round($directly2/$total*100,1) ;
$result2['doubleCounted']['total']= round($double2,1) ;
$result2['doubleCounted']['ratio']= round($double2/$total*100,1) ;
$result2['importedContent']['total'] =  round($imported2,1) ;
$result2['importedContent']['ratio'] =  round($imported2/$total*100,1) ;
$result2['usedInDomesticConsumption']['total'] = round($domestic2,1);
$result2['usedInDomesticConsumption']['ratio'] = round($domestic2/$total*100,1);
$result2['usedInExportProduction']['total'] = round($export2,1);
$result2['usedInExportProduction']['ratio'] = round($export2/$total*100,1);
echo "\n**********2********\n";
echo json_encode($result2);
 $dataInput = json_encode($result2);
$db->update("country_brief",["set2"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);
?>