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

$country = strtolower($_GET['country']);
$year = $_GET['year'];
$tableName = $country . "_" . $year;

$import1 = $db->sum($tableName,"value",[
    "AND"=>[
    variable => ['MVA_FIN', 'MVA_INT','OVA_FIN','OVA_INT'],
    "imp_country[!]"=>['sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'],
    ]

]);


$total = $db->sum($tableName,"value",[
    "AND"=>[
     variable => ['total_export'],
    "imp_country[!]"=>['sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'],
    ]
]);

$export1 = $db->sum($tableName,"value",[
     "AND"=>[
    variable => ['DVA_INTrex1', 'DVA_INTrex2','DVA_INTrex3'],
    "imp_country[!]"=>['sea', 'nca', 'sswa', 'enea', 'pac', 'ap', 'euz', 'eur', 'apta', 'saarc', 'nafta', 'mercosur', 'cptpp', 'rcep', 'apec', 'lac', 'pac_alliance', 'fealac', 'bimstec', 'wld'],
    ]
]);


if($import1> $export1){
    $greensize = round($export1/$import1*100);
    $redsize = 100;
} else {
    $redsize = round($import1/$export1*100);
    $greensize = 100;
}


$result['total_value'] = round(($import1 + $export1));
$result['total_percent'] = round(($import1+ $export1) / $total*100);

$result['import_value'] = round($import1);
$result['import_percent'] = round($import1 / $total*100);

$result['export_value'] = round($export1);
$result['export_percent'] = round($export1 / $total*100);

$result['redsize'] = $redsize;
$result['greensize'] = $greensize;
 echo json_encode($result);



?>
