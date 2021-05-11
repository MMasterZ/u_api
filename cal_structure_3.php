<?php
require_once('connection.php');
require_once('sector_data.php');

$imp_country = $_GET['imp_country'];
$exp_country = $_GET['exp_country'];
$year = $_GET['year'];
$sector = $_GET['sector'];
$table_name = strtolower($exp_country) . "_" . $year;
$table2_name = strtolower($imp_country) . "_" . $year;

//*************Blue bar  ********/
/// calculation of imp_country = $imp_country
if($sector == 0){
    $value1 = $db->sum($table_name,"value",[
      
    imp_country => $imp_country,
    variable => ['DVA_INT', 'DVA_FIN']
]);
} else {
    $value1 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['DVA_INT', 'DVA_FIN'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of imp_country = $exp_country
if($sector == 0){
    $value2 = $db->sum($table2_name,"value",[
    imp_country => $exp_country,
    variable => ['DVA_INT', 'DVA_FIN']
]);
} else {
    $value2 = $db->sum($table2_name,"value",[
    imp_country => $exp_country,
    variable => ['DVA_INT', 'DVA_FIN'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of total_export
if($sector == 0){
    $value_total = $db->sum($table_name,"value",[ 
    imp_country => $imp_country,
    variable => ['total_export']
]);
} else {
    $value_total = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

$result['blue'] = round(($value1 - $value2)/$value_total*100,2);

//*************Red bar  ********/
/// calculation of imp_country = $imp_country
if($sector == 0){
    $value1 = $db->sum($table_name,"value",[

    imp_country => $imp_country,
    variable => ['total_export']
]);
} else {
    $value1 = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of imp_country = $exp_country
if($sector == 0){
    $value2 = $db->sum($table2_name,"value",[

    imp_country => $exp_country,
    variable => ['total_export']
]);
} else {
    $value2 = $db->sum($table2_name,"value",[
    imp_country => $exp_country,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

/// calculation of total_export
if($sector == 0){
    $value_total = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['total_export']
]);
} else {
    $value_total = $db->sum($table_name,"value",[
    imp_country => $imp_country,
    variable => ['total_export'],
    exp_sector=>$sector_data[$sector],
  ]);  
}

$result['red'] = round(($value1 - $value2)/$value_total*100,2);

if($value_total >0.5){
  echo json_encode($result);  
} else {
  $dataShow ['show'] = 'off';
  echo json_encode($dataShow);
}

?>
