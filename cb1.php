<?php
//Directly consumed
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_FIN' or variable='DVA_INT')";
$directly = $db->query($sql)->fetchAll();


//Double
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DDC_FIN' or variable='DDC_INT' or variable='MDC'  or variable='ODC')";
$double = $db->query($sql)->fetchAll();

//Imported content
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'MVA_FIN' or variable='MVA_INT' or variable='OVA_FIN'  or variable='OVA_INT')";
$importedContent = $db->query($sql)->fetchAll();

//Domestic consumption
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'RDV_FIN1' or variable='RDV_FIN2' or variable='RDV_INT')";
$domestic = $db->query($sql)->fetchAll();


//Used in export production
$sql = "select sum(value) as sum from " . $tableName . " where (variable = 'DVA_INTrex1' or variable='DVA_INTrex2' or variable='DVA_INTrex3')";
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


echo "********* 1 ********\n";
echo json_encode($result);

?>