<?php
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];

//*************Blue bar  ********/
/// calculation of imp_country = $imp_country
if($sector == 0){
    $value1 = $db->sum("country_data","value",[

    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INT', 'DVA_FIN', 'DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3']
]);
} else {
    $value1 = $db->sum("country_data","value",[
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INT', 'DVA_FIN', 'DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of imp_country = $exp_country
if($sector == 0){
    $value2 = $db->sum("country_data","value",[

    imp_country => $exp_country,
    year => $year,
    variable => ['DVA_INT', 'DVA_FIN', 'DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3']
]);
} else {
    $value2 = $db->sum("country_data","value",[
    imp_country => $exp_country,
    year => $year,
    variable => ['DVA_INT', 'DVA_FIN', 'DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of total_export
if($sector == 0){
    $value_total = $db->sum("country_data","value",[
    exp_country => $exp_country,  
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export']
]);
} else {
    $value_total = $db->sum("country_data","value",[
    exp_country => $exp_country,  
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

$result['blue'] = ($value1 - $value2)/$value_total*100;

//*************Red bar  ********/
/// calculation of imp_country = $imp_country
if($sector == 0){
    $value1 = $db->sum("country_data","value",[

    imp_country => $imp_country,
    year => $year,
    variable => ['total_export']
]);
} else {
    $value1 = $db->sum("country_data","value",[
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of imp_country = $exp_country
if($sector == 0){
    $value2 = $db->sum("country_data","value",[

    imp_country => $exp_country,
    year => $year,
    variable => ['total_export']
]);
} else {
    $value2 = $db->sum("country_data","value",[
    imp_country => $exp_country,
    year => $year,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of total_export
if($sector == 0){
    $value_total = $db->sum("country_data","value",[
    exp_country => $exp_country,  
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export']
]);
} else {
    $value_total = $db->sum("country_data","value",[
    exp_country => $exp_country,  
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

$result['red'] = ($value1 - $value2)/$value_total*100;

echo json_encode($result);
?>
