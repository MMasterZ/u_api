<?php
require_once('connection.php');
require_once('sector_data.php');


$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];
$table_name = $exp_country . "_" . $year;

//get region
$region_data = $db->select("country_list","region",[
iso =>$exp_country
]);
$region = $region_data[0];

//get country in same region
$country_data = $db->select("country_list","iso",[
region =>$region
]);

for($i=0; $i<count($country_data);$i++){
  $imp_country = $country_data[$i];

  $result[$i]['imp_country'] = $imp_country;

  /// calculation of imp cons
if($sector == 0){
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_FIN', 'DVA_INT']
]);
} else {
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_FIN', 'DVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result[$i]['imp_cons'] = round($value,2);

/// Calculation of imp exp
if($sector == 0){
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3' ]
]);
} else {
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DVA_INTrex1', 'DVA_INTrex2', 'DVA_INTrex3'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result[$i]['imp_exp'] = round($value,2);

/// Calculation of dom cons
if($sector == 0){
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT' ]
]);
} else {
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['RDV_FIN1', 'RDV_FIN2', 'RDV_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result[$i]['dom_cons'] = round($value,2);

/// Calculation of double
if($sector == 0){
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC' ]
]);
} else {
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['DDC_FIN', 'DDC_INT', 'MDC', 'ODC'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result[$i]['double'] = round($value,2);


/// Calculation of imp cont
if($sector == 0){
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT' ]
]);
} else {
    $value = $db->sum($table_name,"value",[
    exp_country =>$exp_country,
    imp_country => $imp_country,
    year => $year,
    variable => ['MVA_FIN', 'MVA_INT', 'OVA_FIN', 'OVA_INT'],
    exp_sector=>$sector_data[$sector],
  ]);  
}
$result[$i]['imp_cont'] = round($value,2);

}

echo json_encode($result);
?>
