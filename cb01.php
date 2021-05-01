<?php
require_once('connection.php');
 $_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];
// $exp_country = "THA";
// $year = 2017;
$tableName = $exp_country . "_" . $year;


//Set #1
//Directly consumed
$result =array();
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))  ";
$directly = $db->query($sql)->fetchAll();


//Double
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC'  or variable='ODC')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$double = $db->query($sql)->fetchAll();

//Imported content
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN'  or variable='OVA_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$importedContent = $db->query($sql)->fetchAll();

//Domestic consumption
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$domestic = $db->query($sql)->fetchAll();


//Used in export production
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')  and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
$usedInExport = $db->query($sql)->fetchAll();

$total = round($directly[0][0],1) + round($double[0][0],1) + round($importedContent[0][0],1) + round($domestic[0][0],1) + round($usedInExport[0][0],1) ;

$result['directlyConsumed']['total'] =round($directly[0][0],1) ;
$result['directlyConsumed']['ratio'] =round($directly[0][0]/$total*100,1) ;
$result['doubleCounted']['total'] = round($double[0][0],1);
$result['doubleCounted']['ratio'] =round($double[0][0]/$total*100,1) ;
$result['importedContent']['total'] = round($importedContent[0][0],1);
$result['importedContent']['ratio'] = round($importedContent[0][0]/$total*100,1) ;
$result['usedInDomesticConsumption']['total'] = round($domestic[0][0],1);
$result['usedInDomesticConsumption']['ratio'] = round($domestic[0][0]/$total*100,1) ;
$result['usedInExportProduction']['total'] = round($usedInExport[0][0],1);
$result['usedInExportProduction']['ratio'] =  round($usedInExport[0][0]/$total*100,1) ;



 $dataInput = json_encode($result);
$db->update("country_brief",["set1"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);

//Set #3
//get country in same region

if($year == 2017){
    $result3 = '{"directlyConsumed":{"total":11883112.4,"ratio":52.7},"doubleCounted":{"total":1909054.4,"ratio":8.5},"importedContent":{"total":4383306.6,"ratio":19.4},"usedInDomesticConsumption":{"total":491838.7,"ratio":2.2},"usedInExportProduction":{"total":3894623.5,"ratio":17.3}}';
    $dataInput = $result3;

} else if($year == 2007){
    $result3 = '{"directlyConsumed":{"total":8873301.9,"ratio":53.6},"doubleCounted":{"total":1320346.4,"ratio":8},"importedContent":{"total":3179278.6,"ratio":19.2},"usedInDomesticConsumption":{"total":308201.2,"ratio":1.9},"usedInExportProduction":{"total":2871238.8,"ratio":17.3}}';
    $dataInput = $result3;

} else if($year == 2011){
    $result3 = '{"directlyConsumed":{"total":11285691.9,"ratio":52.9},"doubleCounted":{"total":1765818.4,"ratio":8.3},"importedContent":{"total":4141543,"ratio":19.4},"usedInDomesticConsumption":{"total":416205.1,"ratio":2},"usedInExportProduction":{"total":3730918.3,"ratio":17.5}}';
    $dataInput = $result3;
}  
else {
//get country in same region
$country_data = $db->select("country_list","iso");

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


$result3['directlyConsumed']['total']  = round($directly2,1) ;
$result3['directlyConsumed']['ratio']  = round($directly2/$total*100,1) ;
$result3['doubleCounted']['total']= round($double2,1) ;
$result3['doubleCounted']['ratio']= round($double2/$total*100,1) ;
$result3['importedContent']['total'] =  round($imported2,1) ;
$result3['importedContent']['ratio'] =  round($imported2/$total*100,1) ;
$result3['usedInDomesticConsumption']['total'] = round($domestic2,1);
$result3['usedInDomesticConsumption']['ratio'] = round($domestic2/$total*100,1);
$result3['usedInExportProduction']['total'] = round($export2,1);
$result3['usedInExportProduction']['ratio'] = round($export2/$total*100,1);
// echo "\n**********3********\n";
print_r($result3);
 $dataInput = json_encode($result3);
 echo $dataInput;
}


//  $dataInput = json_encode($result3);

$db->update("country_brief",["set3"=>$dataInput],["AND"=>["economy"=>$exp_country,"year"=>$year]]);

echo "finish";
?>