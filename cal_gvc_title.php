<?php 
// หน้า getStarted
// [$country]’s GVC exports amount to ['total_percent']% ($['total_value'] billion) of its gross exports in [$year]

// Imported content comprising ['import_percent']% ($['import_value'] billion) of gross exports

// Export of intermediates used in further export production comprising ['export_percent']% ($['export_value'] billion) of gross exports


// Imported content used in exports (Backward linkages)
// Share: ['import_percent']% of gross exports
// Value: $['import_value'] billion
//วงกลมสีส้ม ขนาดแปรผันตาม ['redsize']


// Export of intermediates used in export production (Forward linkages)
// Share: ['export_percent']% of gross exports
// Value: $['export_value'] billion
//วงกลมสีเขียว ขนาดแปรผันตาม ['greensize']

require_once('connection.php');

$country = $_GET['country'];
$year = $_GET['year'];
$tableName = $country . "_" . $year;

$import1 = $db->sum($tableName,"value",[
    exp_country =>$country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT','OVA_FIN','OVA_INT']
]);
$total = $db->sum($tableName,"value",[
    exp_country =>$country,
    year => $year,
    variable => ['total_export']
]);

$export1 = $db->sum($tableName,"value",[
    exp_country =>$country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2','DVA_INTrex3']
]);



if($export1 > $import1){
    $greensize = round($import1/$export1*100);
    $redsize = 100;
} else {
    $redsize = round($export1/$import1*100);
    $greensize = 100;
}


$result['total_value'] = round(($import1 + $export1)/1000);
$result['total_percent'] = round(($import1+ $export1) / $total*100);

$result['import_value'] = round($import1/1000);
$result['import_percent'] = round($import1 / $total*100);

$result['export_value'] = round($export1/1000);
$result['export_percent'] = round($export1 / $total*100);

$result['redsize'] = $redsize;
$result['greensize'] = $greensize;
 echo json_encode($result);



?>