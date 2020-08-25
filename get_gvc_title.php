<?php 
require_once('connection.php');

$country = $_GET['country'];
$year = $_GET['year'];

$import1 = $db->sum("country_data","value",[
    exp_country =>$country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT','OVA_FIN','OVA_INT']
]);
$total = $db->sum("country_data","value",[
    exp_country =>$country,
    year => $year,
    variable => ['total_export']
]);

$export1 = $db->sum("country_data","value",[
    exp_country =>$country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2','DVA_INTrex3']
]);

$result['total_value'] = $import1 + $export1;
$result['total_percent'] = ($import1+ $export1) / $total*100;

$result['import_value'] = $import1;
$result['import_percent'] = $import1 / $total*100;

$result['export_value'] = $export1;
$result['export_percent'] = $export1 / $total*100;
 echo json_encode($result);




?>
