<?php
require_once('connection.php');
$_POST = json_decode(file_get_contents("php://input"),true);
$exp_country = $_POST['country'];
$year = $_POST['year'];
// $exp_country = 'THA';
// $year = 2017;

$db->delete("countrybriefset3",["year"=>$year]);

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
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DVA_FIN' or variable='DVA_INT') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
    $directly = $db->query($sql)->fetchAll();
    $directly2 = $directly2 + $directly[0][0];

    //Double
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC'  or variable='ODC') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
    $double = $db->query($sql)->fetchAll();
    $double2 = $double2 + $double[0][0];

    //Imported content
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN'  or variable='OVA_INT') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
    $importedContent = $db->query($sql)->fetchAll();
    $imported2 = $imported2 + $importedContent[0][0];

    //Domestic consumption
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
    $domestic = $db->query($sql)->fetchAll();
    $domestic2 = $domestic2 + $domestic[0][0];

    //Used in export production
    $sql = "select sum(value) as sum from " . $tableName2 . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3') and ( imp_country NOT IN ('sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'))";
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

 $dataInput = json_encode($result3);
 $db->insert("countrybriefset3",["set3"=>$dataInput,"year"=>$year]);

echo "finish";
?>