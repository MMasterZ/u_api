<?php
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];

/// calculation of imp cons
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_FIN', 'DVA_INT']
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_FIN', 'DVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['imp_cons'] = $value;


/// Calculation of imp exp
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3' ]
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['imp_exp'] = $value;

/// Calculation of dom cons
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT' ]
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['dom_cons'] = $value;

/// Calculation of double
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC' ]
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['double'] = $value;


/// Calculation of imp cont
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT' ]
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['imp_cont'] = $value;

//Gross exports to import country
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export' ]
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['text_export_to_import_country'] = $value/1000;

//Gross exports to world
if($sector == 0){
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    year => $year,
    variable => ['total_export' ]
]);
} else {
    $value = $db->sum("country_data","value",[
    exp_country =>$exp_country,
    year => $year,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result['text_export_to_world'] = $value/1000;




echo json_encode($result);
?>
